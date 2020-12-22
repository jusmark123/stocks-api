<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Constants\Transport\JobConstants;
use App\Event\Job\JobInitiatedEvent;
use App\Event\JobItem\JobItemProcessedEvent;
use App\Event\JobItem\JobItemQueuedEvent;
use App\Event\OrderInfo\OrderInfoProcessedEvent;
use App\Event\OrderInfo\OrderInfoPublishedEvent;
use App\Event\OrderInfo\OrderInfoReceivedEvent;

/**
 * Class OrderInfoEventSubscriber.
 */
class OrderInfoEventSubscriber extends AbstractJobEventSubscriber
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OrderInfoReceivedEvent::getEventName() => [
                [
                    'receive',
                ],
            ],
            OrderInfoProcessedEvent::getEventName() => [
                [
                    'processed',
                ],
            ],
            OrderInfoPublishedEvent::getEventName() => [
                [
                    'published',
                ],
            ],
        ];
    }

    /**
     * @param OrderInfoProcessedEvent $event
     *
     * @throws \Exception
     */
    public function processed(OrderInfoProcessedEvent $event)
    {
        $this->dispatch(new JobItemProcessedEvent($event->getJobItem()));
    }

    /**
     * @param OrderInfoPublishedEvent $event
     */
    public function published(OrderInfoPublishedEvent $event)
    {
        $this->dispatch(new JobItemQueuedEvent($event->getJobItem()));
    }

    /**
     * @param OrderInfoReceivedEvent $event
     */
    public function receive(OrderInfoReceivedEvent $event)
    {
        $job = $event->getJob();
        if (JobConstants::JOB_INITIATED === $job->getStatus()) {
            $this->dispatch(new JobInitiatedEvent($job));
        }
    }
}
