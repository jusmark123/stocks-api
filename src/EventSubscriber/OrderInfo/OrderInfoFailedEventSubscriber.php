<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber\OrderInfo;

use App\Event\Job\JobIncompleteEvent;
use App\Event\OrderInfo\OrderInfoFailedEventInterface;
use App\Event\OrderInfo\OrderInfoProcessFailedEvent;
use App\Event\OrderInfo\OrderInfoPublishFailedEvent;
use App\Event\OrderInfo\OrderInfoReceiveFailedEvent;
use App\EventSubscriber\AbstractMessageEventSubscriber;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class OrderInfoFailedEventSubscriber.
 */
class OrderInfoFailedEventSubscriber extends AbstractMessageEventSubscriber
{
    /**
     * OrderInfoFailedEventSubscriber constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     * @param SerializerInterface      $serializer
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger,
        SerializerInterface $serializer
    ) {
        parent::__construct($dispatcher, $logger, $serializer);
    }

    /**
     * @return \string[][][]
     */
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

    /**
     * @param OrderInfoReceiveFailedEvent $event
     */
    public function orderInfoReceiveFailed(OrderInfoReceiveFailedEvent $event)
    {
        $this->jobIncomplete($event);
    }

    /**
     * @param OrderInfoProcessFailedEvent $event
     */
    public function orderInfoProcessFailed(OrderInfoProcessFailedEvent $event)
    {
        $this->jobIncomplete($event);
    }

    /**
     * @param OrderInfoPublishFailedEvent $event
     */
    public function orderInfoPublishFailed(OrderInfoPublishFailedEvent $event)
    {
        $this->jobIncomplete($event);
    }

    /**
     * @param OrderInfoFailedEventInterface $event
     */
    private function createLogEntry(OrderInfoFailedEventInterface $event)
    {
        $orderInfoMessage = $event->getOrderInfoMessage();
        $orderInfo = $event->getOrderInfo();
        $job = $event->getJob();
        $this->logger->error($event->getException()->getMessage(), [
            'exception' => $event->getException(),
            'order_info_message' => $orderInfoMessage ?? json_decode(
                $this->serializer->serialize($event->getOrderInfoMessage(), self::ENTITY_LOG_FORMAT),
                true),
            'orderInfo' => $orderInfo ?? json_decode(
                $this->serializer->serialize($orderInfo, self::ENTITY_LOG_FORMAT),
                true),
            'jobUUID' => $job->getId(),
        ]);
    }

    /**
     * @param OrderInfoFailedEventInterface $event
     */
    private function jobIncomplete(OrderInfoFailedEventInterface $event)
    {
        $this->dispatcher->dispatch(
            new JobIncompleteEvent($event->getJob(), $event->getException()),
            JobIncompleteEvent::getEventName());
        $this->createLogEntry($event);
    }
}
