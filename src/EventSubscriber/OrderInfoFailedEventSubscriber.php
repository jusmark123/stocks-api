<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\AbstractJobFailedEvent;
use App\Event\Job\JobIncompleteEvent;
use App\Event\JobItem\JobItemProcessFailedEvent;
use App\Event\JobItem\JobItemQueueFailedEvent;
use App\Event\JobItem\JobItemReceiveFailedEvent;
use App\Event\OrderInfo\OrderInfoProcessFailedEvent;
use App\Event\OrderInfo\OrderInfoPublishFailedEvent;
use App\Event\OrderInfo\OrderInfoReceiveFailedEvent;

/**
 * Class OrderInfoFailedEventSubscriber.
 */
class OrderInfoFailedEventSubscriber extends AbstractJobEventSubscriber
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
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
        $this->dispatch(new JobItemReceiveFailedEvent(
            $event->getException(),
            $event->getJob(),
            $event->getJobItem())
        );
        $this->jobIncomplete($event);
    }

    /**
     * @param OrderInfoProcessFailedEvent $event
     */
    public function orderInfoProcessFailed(OrderInfoProcessFailedEvent $event)
    {
        $this->dispatcher->dispatch(new JobItemProcessFailedEvent(
            $event->getException(),
            $event->getJob(),
            $event->getJobItem()
        ));
        $this->jobIncomplete($event);
    }

    /**
     * @param OrderInfoPublishFailedEvent $event
     */
    public function orderInfoPublishFailed(OrderInfoPublishFailedEvent $event)
    {
        $this->dispatcher->dispatch(new JobItemQueueFailedEvent(
            $event->getException(),
            $event->getJob(),
            $event->getJobItem()
        ));
        $this->jobIncomplete($event);
    }

    /**
     * @param AbstractJobFailedEvent $event
     */
    private function jobIncomplete(AbstractJobFailedEvent $event)
    {
        $this->dispatcher->dispatch(
            new JobIncompleteEvent($event->getException(), $event->getJob(), $event->getJobItem())
        );
        $this->logError($event);
    }
}
