<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\DTO\Aws\Sns\SubscriptionRequest;
use App\Entity\TopicSubscription;
use App\Service\TopicService;

class TopicSubscribeDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @var TopicService
     */
    private TopicService $topicService;

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
        return $data instanceof SubscriptionRequest;
    }

    /**
     * @param       $data
     * @param array $context
     *
     * @return TopicSubscription|object|void
     */
    public function persist($data, array $context = [])
    {
        return $this->topicService->subscribe($data);
    }

    /**
     * @param       $data
     * @param array $context
     *
     * @return \App\Entity\AbstractEntity
     */
    public function remove($data, array $context = [])
    {
        $entityManager = $this->topicService->getSubscriptionEntityManager();

        if ($data->isConfirmed()) {
            $this->topicService->unsubscribe($data);
        }

        $entityManager->remove($data, true);

        return $data;
    }
}
