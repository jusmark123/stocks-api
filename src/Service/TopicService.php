<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use ApiPlatform\Core\Exception\InvalidArgumentException;
use ApiPlatform\Core\Exception\ItemNotFoundException;
use App\DTO\Aws\Sns\Notification;
use App\DTO\Aws\Sns\PublishMessageRequest;
use App\DTO\Aws\Sns\PublishMessageResponse;
use App\DTO\Aws\Sns\SubscriptionRequest;
use App\Entity\Factory\TopicFactory;
use App\Entity\Factory\TopicSubscriptionFactory;
use App\Entity\Manager\TopicEntityManager;
use App\Entity\Manager\TopicSubscriptionEntityManager;
use App\Entity\Topic;
use App\Entity\TopicSubscription;
use App\Helper\SerializerHelper;
use Aws\Sns\SnsClient;
use Predis\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class TopicService.
 */
class TopicService
{
    const MESSAGE_KEY = 'notifications:%s:%s';

    /**
     * @var SnsClient
     */
    private $client;

    /**
     * @var Client
     */
    private $cache;

    /**
     * @var TopicEntityManager
     */
    private $topicEntityManager;

    /**
     * @var TopicSubscriptionEntityManager
     */
    private $subscriptionEntityManager;

    /**
     * @var string
     */
    private $systemIp;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var string
     */
    private $urlPrefix;

    /**
     * TopicService constructor.
     *
     * @param Client                         $cache
     * @param LoggerInterface                $logger
     * @param RequestStack                   $requestStack
     * @param TopicEntityManager             $topicEntityManager
     * @param TopicSubscriptionEntityManager $topicSubscriptionEntityManager
     * @param string                         $urlPrefix
     * @param string                         $systemIp
     * @param string|null                    $region
     */
    public function __construct(
        Client $cache,
        LoggerInterface $logger,
        RequestStack $requestStack,
        TopicEntityManager $topicEntityManager,
        TopicSubscriptionEntityManager $topicSubscriptionEntityManager,
        string $urlPrefix,
        string $systemIp,
        string $region
    ) {
        $this->cache = $cache;
        $this->client = new SnsClient([
            'version' => 'latest',
            'region' => $region,
        ]);
        $this->topicEntityManager = $topicEntityManager;
        $this->subscriptionEntityManager = $topicSubscriptionEntityManager;
        $this->logger = $logger;
        $this->requestStack = $requestStack;
        $this->systemIp = $systemIp;
        $this->urlPrefix = $urlPrefix;
    }

    /**
     * @param string $token
     * @param string $topicArn
     */
    public function confirmSubscription(string $token, string $topicArn)
    {
        try {
            $result = $this->client->confirmSubscription([
                'Token' => $token,
                'TopicArn' => $topicArn,
            ]);

            $subscription = $this->getTopicSubscriptionFromSubscriptionArn($result->get('SubscriptionArn'));
            $subscription->setConfirmed(true);
            $this->subscriptionEntityManager->persist($subscription, true);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), [
                'message' => $e->getMessage(),
                'class' => self::class,
                'line' => $e->getLine(),
            ]);
        }
    }

    /**
     * @param Topic $topic
     *
     * @throws \Exception
     *
     * @return Topic
     */
    public function createTopic(Topic $topic): Topic
    {
        try {
            $result = $this->client->createTopic([
                'Attributes' => $topic->getAttributes(),
                'Name' => $topic->getName(),
                'Tags' => $topic->getTags(),
            ]);

            $topic->setTopicArn($result->get('TopicArn'));
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), [
                'message' => $e->getMessage(),
                'class' => self::class,
                'line' => $e->getLine(),
            ]);

            throw $e;
        }

        $this->topicEntityManager->persist($topic);

        return $topic;
    }

    public function createSubscription(Topic $topic)
    {
        return TopicSubscriptionFactory::create($topic);
    }

    /**
     * @param Topic $topic
     */
    public function deleteTopic(Topic $topic)
    {
        try {
            $this->client->deleteTopic(['TopicArn' => $topic->getTopicArn()]);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), [
                'message' => $e->getMessage(),
                'class' => self::class,
                'line' => $e->getLine(),
            ]);
        }

        $this->topicEntityManager->remove($topic);
    }

    /**
     * @return TopicSubscriptionEntityManager
     */
    public function getSubscriptionEntityManager(): TopicSubscriptionEntityManager
    {
        return $this->subscriptionEntityManager;
    }

    /**
     * @return TopicEntityManager
     */
    public function getTopicEntityManager(): TopicEntityManager
    {
        return $this->topicEntityManager;
    }

    /**
     * @param string $topicArn
     *
     * @return Topic|null
     */
    public function getTopicFromTopicArn(string $topicArn): ?Topic
    {
        return $this->topicEntityManager->findOneBy(['topicArn' => $topicArn]);
    }

    /**
     * @param string $subscriptionArn
     *
     * @return TopicSubscription|null
     */
    public function getTopicSubscriptionFromSubscriptionArn(string $subscriptionArn): ?TopicSubscription
    {
        return $this->subscriptionEntityManager->findOneBy(['subscriptionArn' => $subscriptionArn]);
    }

    /**
     * @param PublishMessageRequest $request
     */
    public function publish(PublishMessageRequest $request)
    {
        $params = [];
        $topic = $request->getTopic();
        $serializer = SerializerHelper::ObjectNormalizer();

        foreach ($serializer->normalize($request) as $key => $value) {
            if (null !== $value && !empty($value)) {
                if ('topic' === $key) {
                    $key = 'topicArn';
                    $value = $topic->getTopicArn();
                }
                $params[ucfirst($key)] = $value;
            }
        }

        $result = $this->client->publish($params);

        return (new PublishMessageResponse())
            ->setMessageId($result->get('MessageId'))
            ->setSequenceNumber($result->get('SequenceNumber') ?? null);
    }

    /**
     * @param Notification $message
     */
    public function receive(Notification $message)
    {
        $messageType = $this->requestStack->getCurrentRequest()->headers->get('x-amz-sns-message-type');
        $topic = $this->getTopicFromTopicArn($message->getTopicArn());

        if (!$topic instanceof Topic) {
            throw new ItemNotFoundException($this->topicEntityManager::TOPIC_NOT_FOUND);
        }

        if ('SubscriptionConfirmation' === $messageType) {
            $this->confirmSubscription($message->getToken(), $message->getTopicArn());
        }

        $this->logger->info($message->getMessage(), [
            'topicName' => $topic->getName(),
            'topicArn' => $message->getTopicArn(),
            'topicUUID' => $topic->getGuid()->toString(),
        ]);

        $this->cache->setex(
            sprintf(self::MESSAGE_KEY, $topic->getName(), $message->getMessageId()),
            1500,
            $message->getMessage()
        );
    }

    /**
     * @param SubscriptionRequest $request
     *
     * @throws \Exception
     *
     * @return TopicSubscription
     */
    public function subscribe(SubscriptionRequest $request): TopicSubscription
    {
        try {
            if (null === $request->getTopic()) {
                if (null === $request->getTopicArn()) {
                    throw new InvalidArgumentException('One of Topic or TopicArn must be supplied in topic subscription request');
                }
                $topic = $this->getTopicFromTopicArn($request->getTopicArn());
                if (!$topic instanceof Topic) {
                    throw new ItemNotFoundException($this->topicEntityManager::TOPIC_NOT_FOUND);
                }
                $request->setTopic($topic);
            }

            if (null === $request->getEndpoint()) {
                $endpoint = sprintf('%s://%s%s/topics/listen',
                    $request->getProtocol(),
                    $this->systemIp,
                    $this->urlPrefix
                );

                $request->setEndpoint($endpoint);
            }

            $result = $this->client->subscribe([
                'Attributes' => $request->getAttributes(),
                'Endpoint' => $request->getEndpoint(),
                'Protocol' => $request->getProtocol(),
                'ReturnSubscriptionArn' => $request->shouldReturnSubscriptionArn(),
                'TopicArn' => $request->getTopic()->getTopicArn(),
            ]);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), [
                'message' => $e->getMessage(),
                'class' => self::class,
                'line' => $e->getLine(),
            ]);

            throw $e;
        }

        $subscription = TopicSubscriptionFactory::create($request->getTopic())
            ->setEndpoint($request->getEndpoint())
            ->setConfirmed(false)
            ->setSubscriptionArn($result->get('SubscriptionArn'));
        $this->subscriptionEntityManager->persist($subscription, true);

        return $subscription;
    }

    public function syncTopics(): void
    {
        $result = $this->client->listTopics();

        foreach ($result->get('Topics') as $topicArn) {
            $topicArn = $topicArn['TopicArn'];
            $topic = $this->getTopicFromTopicArn($topicArn);
            if (!$topic instanceof Topic) {
                $results = $this->client->getTopicAttributes(['TopicArn' => $topicArn]);
                $topicAttributes = $results->get('Attributes');

                $topic = TopicFactory::create()
                    ->setName($topicAttributes['DisplayName'])
                    ->setTopicArn($topicAttributes['TopicArn'])
                    ->setAttributes($topicAttributes);

                $this->topicEntityManager->persist($topic);
            }
        }
        $this->topicEntityManager->flush();
    }

    public function syncSubscriptions(): void
    {
        $result = $this->client->listSubscriptions();

        foreach ($result->get('Subscriptions') as $data) {
            $subscriptionArn = $data['SubscriptionArn'];
            $subscription = $this->getTopicSubscriptionFromSubscriptionArn($subscriptionArn);

            if (!$subscription instanceof TopicSubscription) {
                $topicArn = $data['TopicArn'];
                $confirmed = 7 === \count(explode(':', $subscriptionArn));
                $topic = $this->getTopicFromTopicArn($topicArn);

                $subscription = TopicSubscriptionFactory::create($topic)
                    ->setEndpoint($data['Endpoint'])
                    ->setProtocol($data['Protocol'])
                    ->setConfirmed($confirmed);

                $this->subscriptionEntityManager->persist($subscription);
            }
        }
        $this->subscriptionEntityManager->flush();
    }

    /**
     * @param TopicSubscription $subscription
     *
     * @return TopicSubscription
     */
    public function unsubscribe(TopicSubscription $subscription): TopicSubscription
    {
        try {
            $this->client->unsubscribe(['SubscriptionArn' => $subscription->getSubscriptionArn()]);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), [
                'message' => $e->getMessage(),
                'class' => self::class,
                'line' => $e->getLine(),
            ]);
        }

        return $subscription;
    }
}
