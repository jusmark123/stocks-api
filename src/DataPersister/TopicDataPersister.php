<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Topic;
use App\Service\TopicService;

/**
 * Class TopicDataPersister.
 */
class TopicDataPersister implements ContextAwareDataPersisterInterface
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
        return $data instanceof Topic;
    }

    /**
     * @param       $data
     * @param array $context
     *
     * @throws \Exception
     *
     * @return Topic|object|void
     */
    public function persist($data, array $context = [])
    {
        return $this->topicService->createTopic($data);
    }

    /**
     * @param       $data
     * @param array $context
     *
     * @return mixed
     */
    public function remove($data, array $context = [])
    {
        $this->topicService->deleteTopic($data);

        return $data;
    }
}
