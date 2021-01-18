<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\DTO\Aws\Sns\Notification;
use App\Entity\TopicSubscription;
use App\Service\TopicService;

/**
 * Class TopicListenDataPersister.
 */
class TopicListenDataPersister implements ContextAwareDataPersisterInterface
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
        return $data instanceof Notification;
    }

    /**
     * @param       $data
     * @param array $context
     *
     * @return TopicSubscription|object|void
     */
    public function persist($data, array $context = [])
    {
        $this->topicService->receive($data);

        return $data;
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
