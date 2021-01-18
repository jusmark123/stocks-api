<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\DTO\Aws\Sns\PublishMessageRequest;
use App\Service\TopicService;

/**
 * Class TopicPublishDataPersister.
 */
class TopicPublishDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @var TopicService
     */
    private $topicService;

    /**
     * TopicSubscribeDataPersister constructor.
     *
     * @param TopicService $topicService
     */
    public function __construct(TopicService $topicService)
    {
        $this->topicService = $topicService;
    }

    /**
     * @param       $data
     * @param array $context
     *
     * @return bool
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof PublishMessageRequest;
    }

    /**
     * @param       $data
     * @param array $context
     *
     * @return \App\DTO\Aws\Sns\PublishMessageResponse|object|void
     */
    public function persist($data, array $context = [])
    {
        return $this->topicService->publish($data);
    }

    /**
     * @param       $data
     * @param array $context
     */
    public function remove($data, array $context = [])
    {
        // TODO: Implement remove() method.
    }
}
