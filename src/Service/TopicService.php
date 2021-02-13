<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use ApiPlatform\Core\Exception\InvalidArgumentException;
use ApiPlatform\Core\Exception\ItemNotFoundException;
use App\Constants\Brokerage\TdAmeritradeConstants;
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
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;

/**
 * Class TopicService.
 */
class TopicService
{
    const MESSAGE_KEY = 'notifications:%s:%s';
    const NAMESPACE = '77b0c9ee-69d7-11eb-9439-0242ac130002';
    const SERIALIZATION = '/opt/app-root/src/config/serialization/td_ameritrade_order_info.yml';

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
            $this->client->confirmSubscription([
                'Token' => $token,
                'TopicArn' => $topicArn,
            ]);

            return $this->getTopic($topicArn, true);
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
            $this->saveTopicToCache($topic);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), [
                'message' => $e->getMessage(),
                'class' => self::class,
                'line' => $e->getLine(),
            ]);

            throw $e;
        }

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
    }

    /**
     * @param bool $fetchSubscriptions
     *
     * @return array
     */
    public function listTopics($fetchSubscriptions = false)
    {
        $nextToken = null;
        $topics = [];
        $params = [];

        $topicIds = $this->cache->keys('topics:*');

        if ($topicIds) {
            foreach ($topicIds as $topicId) {
                $topic = unserialize($this->cache->get($topicId));
                if ($topic instanceof Topic) {
                    $topics[] = $topic;
                }
            }
        } else {
            do {
                $results = $this->client->listTopics($params);
                $nextToken = $results->get('NextToken');
                $params['NextToken'] = $nextToken;

                foreach ($results->get('Topics') as $topic) {
                    $topic = $this->getTopic($topic['TopicArn'], $fetchSubscriptions);
                    $topics[] = $topic;
                }
            } while (null !== $nextToken);
        }

        return $topics;
    }

    /**
     * @param Topic|null $topic
     *
     * @return array
     */
    public function listSubscriptions(?Topic $topic = null)
    {
        $params = [];
        $nextToken = null;
        $subscriptions = [];

        do {
            if (null !== $topic) {
                $params['TopicArn'] = $topic->getTopicArn();
                $results = $this->client->listSubscriptionsByTopic($params);
            } else {
                $results = $this->client->listSubscriptions($params);
            }
            $nextToken = $results->get('NextToken');
            $params['NextToken'] = $nextToken;

            foreach ($results->get('Subscriptions') as $subscription) {
                $subscriptionArn = $subscription['SubscriptionArn'];
                $subscription = $this->getSubscription($subscriptionArn, $subscription, $topic);
                $subscriptions[] = $subscription;
            }
        } while (null !== $nextToken);

        return $subscriptions;
    }

    /**
     * @param string $topicArn
     * @param bool   $fetchSubscriptions
     *
     * @return Topic
     */
    public function getTopic(string $topicArn, $fetchSubscriptions = false)
    {
        $topic = $this->getTopicFromCache($topicArn);

        if (!$topic instanceof Topic) {
            if (Uuid::isValid($topicArn)) {
                $this->listTopics(true);
                $topic = $this->getTopicFromCache($topicArn);
            } else {
                $results = $this->client->getTopicAttributes(['TopicArn' => $topicArn]);
                $attributes = $results->get('Attributes');
                $attributes = $this->normalizeAttributes($attributes);
                $topic = TopicFactory::create()
                    ->setName($attributes->getDisplayName())
                    ->setTopicArn($topicArn)
                    ->setType(\array_key_exists('FifoTopic', $attributes) ? 'fifo' : 'standard')
                    ->setContentBasedDeduplication(\array_key_exists('ContentBasedDeduplication', $attributes))
                    ->setAttributes($attributes);
                $this->saveTopicToCache($topic);
            }
        }

        if ($fetchSubscriptions) {
            $subscriptions = $this->listSubscriptions($topic);
            $topic->setSubscriptions($subscriptions);
        }

        return $topic;
    }

    /**
     * @param string     $subscriptionArn
     * @param array|null $data
     * @param Topic|null $topic
     *
     * @return TopicSubscription
     */
    public function getSubscription(string $subscriptionArn, array $data, ?Topic $topic = null): TopicSubscription
    {
        $attributes = [];
        if (6 === \count(explode(':', $subscriptionArn))) {
            $result = $this->client->getSubscriptionAttributes(['SubscriptionArn' => $subscriptionArn]);
            $attributes = $result->get('Attributes');
        } else {
            $subscriptionArn = null;
        }

        if (null === $topic) {
            $topic = $this->getTopic($data['TopicArn']);
        }

        return TopicSubscriptionFactory::create($topic)
            ->setEndpoint($data['Endpoint'])
            ->setProtocol($data['Protocol'])
            ->setSubscriptionArn($subscriptionArn)
            ->setConfirmed(null !== $subscriptionArn)
            ->setTopic($topic)
            ->setAttributes($attributes);
    }

    /**
     * @param PublishMessageRequest $request
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     *
     * @return PublishMessageResponse
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
        $topic = $this->getTopic($message->getTopicArn());

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
                $topic = $this->getTopic($request->getTopicArn());
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

        return $subscription;
    }

    /**
     * @return array
     */
    public function syncTopics(): array
    {
        $topics = [];
        $result = $this->client->listTopics();

        foreach ($result->get('Topics') as $topicArn) {
            $topicArn = $topicArn['TopicArn'];
            $topic = $this->getTopic($topicArn);
            if (!$topic instanceof Topic) {
                $results = $this->client->getTopicAttributes(['TopicArn' => $topicArn]);
                $topicAttributes = $results->get('Attributes');

                $topic = TopicFactory::create()
                    ->setName($topicAttributes['DisplayName'])
                    ->setTopicArn($topicAttributes['TopicArn'])
                    ->setAttributes($topicAttributes);

                $this->topicEntityManager->persist($topic);
            }
            $topics[] = $topic;
        }

        $this->topicEntityManager->flush();

        return $topics;
    }

    /**
     * @return array
     */
    public function syncSubscriptions(): array
    {
        $result = $this->client->listSubscriptions();
        $subscriptions = $result->get('Subscriptions');

        if ($subscriptions) {
            foreach ($subscriptions as $data) {
                $subscriptionArn = $data['SubscriptionArn'];
                $subscription = $this->getSubscription($subscriptionArn, $data);

                if (!$subscription instanceof TopicSubscription) {
                    $topicArn = $data['TopicArn'];
                    $confirmed = 7 === \count(explode(':', $subscriptionArn));
                    $topic = $this->getTopic($topicArn);
                    $subscription = TopicSubscriptionFactory::create($topic)
                        ->setEndpoint($data['Endpoint'])
                        ->setProtocol($data['Protocol'])
                        ->setConfirmed($confirmed);

                    $this->subscriptionEntityManager->persist($subscription);
                }
            }
        }

        $this->subscriptionEntityManager->flush();

        return $subscriptions;
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

    /**
     * @param array $attributes
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     *
     * @return mixed
     */
    private function normalizeAttributes(array $attributes)
    {
        $keys = array_keys($attributes);
        $values = array_values($attributes);
        $keys = array_map('lcfirst', $keys);
        $attributes = array_combine($keys, $values);
        $classMetaDataFactory = new ClassMetadataFactory(
            new YamlFileLoader(TdAmeritradeConstants::ORDER_INFO_SERIALIZATION_CONFIG));
        $normalizer = SerializerHelper::CamelCaseToSnakeCaseNormalizer($classMetaDataFactory);

        return $normalizer->denormalize($attributes, 'App\DTO\Aws\Sns\TopicAttributes');
    }

    /**
     * @param Topic $topic
     */
    private function saveTopicToCache(Topic $topic)
    {
        $identifier = Uuid::Uuid5(self::NAMESPACE, $topic->getTopicArn());
        $topic->setGuid($identifier);
        $this->cache->setex(sprintf('topics:%s', $identifier->toString()), 1800, serialize($topic));
    }

    /**
     * @param string $identifier
     *
     * @return Topic|null
     */
    private function getTopicFromCache(string $identifier): ?Topic
    {
        if (\count(explode(':', $identifier)) > 6) {
            $identifier = Uuid::Uuid5(self::NAMESPACE, $identifier)->toString();
        }

        $topic = $this->cache->get(sprintf('topics:%s', $identifier));

        if (null !== $topic) {
            $topic = unserialize($topic);
        }

        return $topic;
    }
}
