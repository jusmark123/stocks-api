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
use App\Event\Ticker\TickerProcessedEvent;
use App\Event\Ticker\TickerPublishedEvent;
use App\Event\Ticker\TickerReceivedEvent;

/**
 * Class TickerEventSubscriber.
 */
class TickerEventSubscriber extends AbstractJobEventSubscriber
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            TickerProcessedEvent::getEventName() => [
                [
                    'processed',
                ],
            ],
            TickerPublishedEvent::getEventName() => [
                [
                    'published',
                ],
            ],
            TickerReceivedEvent::getEventName() => [
                [
                    'receive',
                ],
            ],
        ];
    }

    /**
     * @param TickerProcessedEvent $event
     */
    public function processed(TickerProcessedEvent $event)
    {
        $this->dispatch(new JobItemProcessedEvent($event->getJob(), $event->getJobItem()));
    }

    /**
     * @param TickerReceivedEvent $event
     */
    public function published(TickerReceivedEvent $event)
    {
        $this->dispatch(new JobItemQueuedEvent($event->getJob(), $event->getJobItem()));
    }

    /**
     * @param TickerReceivedEvent $event
     */
    public function receive(TickerReceivedEvent $event)
    {
        $job = $event->getJob();
        if (JobConstants::JOB_INITIATED === $job->getStatus()) {
            $this->dispatch(new JobInitiatedEvent($job));
        }
    }
}
