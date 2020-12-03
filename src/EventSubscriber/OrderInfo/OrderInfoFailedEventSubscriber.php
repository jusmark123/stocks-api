<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber\OrderInfo;

use App\Entity\Job;
use App\Event\AbstractFailedEvent;
use App\Event\OrderInfo\OrderInfoProcessFailedEvent;
use App\Event\OrderInfo\OrderInfoPublishFailedEvent;
use App\Event\OrderInfo\OrderInfoReceiveFailedEvent;
use App\EventSubscriber\AbstractMessageEventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class OrderInfoFailedEventSubscriber extends AbstractMessageEventSubscriber
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        SerializerInterface $serializer
    ) {
        $this->entityManager = $entityManager;
        parent::__construct($logger, $serializer);
    }

    public function getEventSubscribedEvents()
    {
        return [
            OrderInfoReceiveFailedEvent::getEventName() => [
                [
                    'orderInfoReceiveFailed',
                ],
            ],
            OrderInfoProcessFailedEvent::getEventName() => [
                [
                    'orderInfoProcessFailed',
                ],
            ],
            OrderInfoPublishFailedEvent::getEventName() => [
                [
                    'orderInfoPublishFailed',
                ],
            ],
        ];
    }

    public function orderInfoReceiveFailed(OrderInfoReceiveFailedEvent $event)
    {
        $job = $event->getJob();

        $this->logger->error($event->getException()->getMessage(), [
            'exception' => $event->getException(),
            'order_info_message' => json_decode($this->serializer->serialize($event->getMessage(), self::ENTITY_LOG_FORMAT), true),
        ]);
    }

    public function orderInfoProcessFailed(OrderInfoProcessFailedEvent $event)
    {
        $this->createLogEntry($event);
    }

    public function orderInfoPublishFailed(OrderInfoPublishFailedEvent $event)
    {
        $this->createLogEntry($event);
    }

    /**
     * @param AbstractFailedEvent $event
     * @param Job                 $job
     */
    private function createLogEntry(AbstractFailedEvent $event)
    {
        $orderInfo = $event->getOrderInfo();
        $job = $event->getJob();
        $this->logger->error($event->getException()->getMessage(), [
            'exception' => $event->getException(),
            'orderInfo' => json_decode($this->serializer->serialize($orderInfo, self::ENTITY_LOG_FORMAT), true),
            'jobUUID' => $job ? $job->getId() : null,
        ]);
    }
}
