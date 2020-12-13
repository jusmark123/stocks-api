<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\ClientListener;

use App\MessageClient\ClientFactory;
use App\MessageClient\Exception\InvalidPacket;
use App\MessageClient\Exception\QueueConfigurationException;
use App\MessageClient\Protocol\CredentialHandler;
use App\MessageClient\Protocol\Credentials;
use App\MessageClient\Protocol\MessageFactory;
use Bunny\Async\Client;
use Bunny\Channel;
use Bunny\Message;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use React\Promise;
use React\Promise\ExtendedPromiseInterface;

/**
 * Class BunnyListenerProvider.
 */
class BunnyListenerProvider implements ClientListenerProvider, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ClientFactory
     */
    protected $clientFactory;

    /**
     * @var CredentialHandler
     */
    protected $credentialsHandler;

    /**
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * @var ClientListener[]
     */
    protected $listeners;

    /**
     * @var string
     */
    protected $amqpQueueNamePrefix;

    /**
     * @var string
     */
    protected $exchangeName;

    /**
     * BunnyListenerProvider constructor.
     *
     * @param LoggerInterface   $logger
     * @param MessageFactory    $messageFactory
     * @param ClientFactory     $clientFactory
     * @param CredentialHandler $credentialHandler
     * @param string            $amqpQueueNamePrefix
     * @param string            $exchangeName
     * @param array             $listeners
     *
     * @throws QueueConfigurationException
     */
    public function __construct(
        LoggerInterface $logger,
        MessageFactory $messageFactory,
        ClientFactory $clientFactory,
        CredentialHandler $credentialHandler,
        string $amqpQueueNamePrefix,
        string $exchangeName,
        $listeners = []
    ) {
        $this->setLogger($logger);
        $this->messageFactory = $messageFactory;
        $this->clientFactory = $clientFactory;
        $this->credentialsHandler = $credentialHandler;
        $this->amqpQueueNamePrefix = $amqpQueueNamePrefix;
        $this->exchangeName = $exchangeName;

        if ($listeners instanceof \Traversable) {
            $listeners = iterator_to_array($listeners);
        }

        $this->setListeners($listeners);
    }

    /**
     * @return ClientListener[]
     */
    public function getListeners(): array
    {
        return $this->listeners;
    }

    /**
     * @param Channel $wrappedChannel
     *
     * @return BunnyChannel
     */
    public function channelFactory(Channel $wrappedChannel)
    {
        return new BunnyChannel($wrappedChannel, $this->exchangeName);
    }

    /**
     * @param array $listeners
     *
     * @throws QueueConfigurationException
     *
     * @return $this
     */
    public function setListeners(array $listeners): BunnyListenerProvider
    {
        $this->listeners = [];

        foreach ($listeners as $listener) {
            if (!$listener instanceof ClientListener) {
                throw new QueueConfigurationException('listeners must implement('.ClientListener::class.')');
            }
            foreach ($this->setTopicQueues($listener) as $queueName => $topicName) {
                if (\array_key_exists($queueName, $this->listeners)) {
                    throw new QueueConfigurationException('queues cannot have the same name');
                }
                $this->listeners[$queueName] = [
                    'exhangeName' => $listener->getExchangeName(),
                    'topicName' => $topicName,
                    'listener' => $listener,
                ];
            }
        }

        return $this;
    }

    /**
     * @param ClientListener $listener
     *
     * @return \Closure
     */
    protected function consumeListener(ClientListener $listener)
    {
        return function (Message $message, Channel $bunnyChannel) use ($listener) {
            $channel = $this->channelFactory($bunnyChannel);
            $loggingContext = [
                'listener' => \get_class($listener),
                'routingKey' => $message->routingKey,
                'consumerTag' => $message->consumerTag,
            ];
            $this->logger->debug('message translated to packet', $loggingContext);

            try {
                $packet = $this->messageFactory->translateToNativePacket($message);
                $packet->setOriginalMessage($message);
            } catch (InvalidPacket $e) {
                $this->logger->error('Invalid packet received', $loggingContext + [
                        'packet' => $message,
                        'exception' => $e,
                    ]);

                return $channel->ack($message)->then(function () {
                    return true;
                });
            }

            $this->logger->debug('message being consumed', $loggingContext);

            return $listener->consume($packet, $channel)->then(
                function () use ($channel, $packet, $loggingContext) {
                    $this->logger->info('message consumed', $loggingContext);

                    return $channel->ack($packet)->then(function () {
                        return true;
                    });
                },
                function (\Throwable $e) use ($channel, $packet, $loggingContext) {
                    $this->logger->error('error consuming', $loggingContext + [
                            'exception' => $e,
                        ]);

                    return $channel->nack($packet, false, false)->then(function () {
                        return false;
                    });
                })->then(
                function ($acked) use ($loggingContext) {
                    $this->logger->info(
                        ($acked ? 'positive' : 'negative').' acknowledgement',
                        $loggingContext
                    );
                },
                function (\Throwable $e) use ($loggingContext) {
                    $this->logger->error('error during the ack/nack', $loggingContext + [
                            'exception' => $e,
                        ]);
                    throw $e;
                }
            );
        };
    }

    /**
     * @return ExtendedPromiseInterface
     */
    public function registerListeners(): ExtendedPromiseInterface
    {
        return $this->getCredentials()->then(
            function (Credentials $credentials) {
                return $this->clientFactory->createClient($credentials);
            }
        )->then(function (Client $client) {
            $this->client = $client;

            return $this->client->channel();
        })->then(function (Channel $channel) {
            return $channel->qos(
                0,
                1
            )->then(function () use ($channel) {
                return $channel;
            });
        })->then(function (Channel $channel) {
            return $this->setupQueueConsumption($channel);
        })->then(
            function () {
                $this->logger->info('all listeners registered');

                return $this->client;
            },
            function (\Throwable $e) {
                $this->logger->error('failed registering listener', [
                    'exception' => $e,
                ]);
                throw $e;
            }
        );
    }

    /**
     * @return Promise\PromiseInterface|Promise\RejectedPromise|null
     */
    protected function getCredentials()
    {
        try {
            return Promise\resolve($this->credentialsHandler->getCredentials('token'));
        } catch (\Throwable $e) {
            return Promise\reject();
        }
    }

    /**
     * @param Channel $channel
     *
     * @return Promise\Promise|Promise\PromiseInterface
     */
    protected function setupQueueConsumption(Channel $channel)
    {
        $subscriptions = [];

        foreach ($this->getListeners() as $queueName => $listenerSetup) {
            $topicName = $listenerSetup['topicName'];

            /** @var ClientListener $listen */
            $listener = $listenerSetup['listener'];
            $exchange = $listenerSetup['exchange'] ?? null;

            $subscriptions[] = $this->declareTopicQueue($channel, $queueName, $topicName, $exchange)->then(
                function () use ($channel, $queueName, $listener) {
                    return $channel->consume(
                        $this->consumeListener($listener),
                        $queueName
                    );
                }
            )->then(
                function () use ($queueName, $topicName) {
                    $this->logger->debug('setup consuming', [
                        'queue' => $queueName,
                        'topic' => $topicName,
                    ]);
                },
                function (\Throwable $e) use ($queueName, $topicName) {
                    $this->logger->debug('failed consuming', [
                        'exception' => $e,
                        'queue' => $queueName,
                        'topic' => $topicName,
                    ]);
                    throw $e;
                }
            );
        }

        return Promise\all($subscriptions);
    }

    /**
     * @param Channel     $channel
     * @param string      $queueName
     * @param string      $topicName
     * @param string|null $exchangeName
     *
     * @return Promise\PromiseInterface
     */
    protected function declareTopicQueue(
        Channel $channel,
        string $queueName,
        string $topicName,
        ?string $exchangeName = null
    ) {
        $exchangeName = $exchangeName ?? 'amq.topic';

        return $channel->queueDeclare($queueName, false, true)->then(
            function () use ($channel, $queueName, $topicName, $exchangeName) {
                return $channel->queueBind($queueName, $exchangeName, $topicName);
            }
        );
    }

    /**
     * @param ClientListener $listener
     *
     * @throws QueueConfigurationException
     *
     * @return array
     */
    protected function setTopicQueues(ClientListener $listener): array
    {
        $retval = [];

        $reflect = new \ReflectionObject($listener);

        $toSubscribeTopic = $listener->getSubscribedTopics();

        foreach ($toSubscribeTopic as $topicName => $topic) {
            $queueName = implode('.', [
                $this->amqpQueueNamePrefix,
                $reflect->getShortName(),
                $topic,
            ]);

            $retval[$queueName] = $topic;
        }

        if (\count($retval) !== \count($toSubscribeTopic)) {
            throw new QueueConfigurationException('queues cannot have the ame name: '.$reflect->getName());
        }

        return $retval;
    }
}
