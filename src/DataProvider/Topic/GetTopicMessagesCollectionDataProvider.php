<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataProvider\Topic;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Topic;
use App\Service\TopicService;

/**
 * Class GetTopicMessagesCollectionDataProvider.
 */
class GetTopicMessagesCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    const RESOURCE_CLASS = Topic::class;
    const OPERATION_NAME = 'get_messages';

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

    /**
     * @param string      $resourceClass
     * @param string|null $operationName
     * @param array       $context
     *
     * @return bool
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return self::RESOURCE_CLASS === $resourceClass && self::OPERATION_NAME === $operationName;
    }

    /**
     * @param string      $resourceClass
     * @param string|null $operationName
     * @param array       $context
     *
     * @return iterable|void
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $entityManager = $this->topicService->getTopicEntityManager();
    }
}
