<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Client\Stream;

use App\Client\Stream\Protocol\HandlerFactory;
use App\Client\Stream\Protocol\Stream;
use App\Exception\StreamConfigurationException;
use App\MessageClient\ClientFactory;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Protocol\CredentialHandler;
use App\MessageClient\Protocol\Credentials;
use App\MessageClient\Protocol\MessageFactory;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use React\Promise;
use React\Promise\ExtendedPromiseInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class WebSocketHandlerProvider implements StreamHandlerProvider, LoggerAwareInterface
{
    use LoggerAwareTrait;

    const EXCHANGE_NAME = 'amq.fanout';

    /**
     * @var ClientFactory
     */
    protected $clientFactory;

    /**
     * @var CredentialHandler
     */
    protected $credentialHandler;

    /**
     * @var EventDispatcher
     */
    protected $dispactcher;

    /**
     * @var HandlerFactory
     */
    protected $handlerFactory;

    /**
     * @var array
     */
    protected $handlers;

    /**
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * @var ClientPublisher
     */
    protected $publisher;

    public function __construct(
        ClientFactory $clientFactory,
        CredentialHandler $credentialHandler,
        EventDispatcherInterface $dispatcher,
        HandlerFactory $handlerFactory,
        LoggerInterface $logger,
        MessageFactory $messageFactory,

        $handlers = []
    ) {
        $this->clientFactory = $clientFactory;
        $this->credentialHandler = $credentialHandler;
        $this->handlerFactory = $handlerFactory;
        $this->messageFactory = $messageFactory;

        $this->setLogger($logger);

        if ($handlers instanceof \Traversable) {
            $handlers = iterator_to_array($handlers);
        }

        $this->setHandlers($handlers);
    }

    public function setHandlers(array $handlers)
    {
        $this->handlers = [];

        foreach ($handlers as $handler) {
            if (!$handler instanceof StreamHandler) {
                throw new StreamConfigurationException('handler must implement('.StreamHandler::class.')');
            }
            foreach ($this->setStream($handler) as $streamName => $topicName) {
                if (\array_key_exists($streamName, $this->handlers)) {
                    throw new StreamConfigurationException('streams cannot have the same name');
                }
                $this->handlers[$streamName] = [
                    'topicName' => $topicName,
                    'handler' => $handler,
                ];
            }
        }

        return $this;
    }

    public function registerHandlers(): ExtendedPromiseInterface
    {
        return $this->getCredentials()->then(
            function (Credentials $credentials) {
                return $this->handlerFactory->createHandler($credentials);
            }
        )->then(function (StreamHandler $handler) {
            $this->handler = $handler;
        });
    }

    public function getCredentials()
    {
        try {
            return Promise\resolve($this->credentialHandler->getCredentials('token'));
        } catch (\Throwable $e) {
            return Promise\reject();
        }
    }

    public function setupStreamConsumption(Stream $stream)
    {
        $streams = [];

        foreach ($this->getHandlers() as $streamName => $handlerSetup) {
            $handler = $handlerSetup['handler'];
            $streams[] = $this->streamConnect($stream)->then(
                function () use ($stream, $handler) {
                    return $stream->consume($this->consumeHandler($handler));
                }
            )->then(
                function () use ($streamName) {
                    $this->logger->debug('setup stream consuming', [
                        'stream' => $streamName,
                    ]);
                },
                function (\Throwable $e) use ($streamName) {
                    $this->logger->debug('failed stream consuming', [
                       'exception' => $e,
                       'stream' => $streamName,
                    ]);
                    throw $e;
                }
            );
        }

        return Promise\all($streams);
    }

    public function streamConnect(StreamHandler $handler)
    {
        return $handler->connect($stream);
    }

    public function setStream(StreamHandler $handler)
    {
        $retval = [];

        $reflect = new \ReflectionObject($handler);

        $toSubscribeStreams = $handler->getSubscribedStreams();

//        foreach($toSubscribeStreams as $streamName) {
//
//        }

        return $retval;
    }

    public function getHandlers()
    {
        return $this->handlers;
    }

    public function consumeHandler(StreamHandler $handler)
    {
        return $handler;
    }
}
