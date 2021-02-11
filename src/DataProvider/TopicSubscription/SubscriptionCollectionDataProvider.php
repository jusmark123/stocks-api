<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataProvider\TopicSubscription;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\TopicSubscription;
use App\Service\TopicService;

class SubscriptionCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    const RESOURCE_CLASS = TopicSubscription::class;
    const OPERATION_NAME = 'get';

    /**
     * @var TopicService
     */
    private $topicService;

    /**
     * GetTopicMessagesCollectionDataProvider constructor.
     *
     * @param TopicService $topicService
     */
    public function __construct(TopicService $topicService)
    {
        $this->topicService = $topicService;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return self::RESOURCE_CLASS === $resourceClass && self::OPERATION_NAME === $operationName;
    }

    public function getCollection(string $resourceClass, string $operationName = null)
    {
        return $this->topicService->listSubscriptions();
    }
}
