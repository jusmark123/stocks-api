<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber\Job;

use App\Constants\Transport\JobConstants;
use App\Event\JobItem\JobItemCancelledEvent;
use App\Event\JobItem\JobItemProcessedEvent;
use App\Event\JobItem\JobItemProcessingEvent;
use App\Event\JobItem\JobItemQueuedEvent;
use App\Event\JobItem\JobItemReceivedEvent;

/**
 * Class JobItemEventSubscriber.
 */
class JobItemEventSubscriber extends AbstractJobEventSubscriber
{
    const INCOMPLETE_STATUSES = [JobConstants::JOB_PENDING, JobConstants::JOB_QUEUED, JobConstants::JOB_IN_PROGRESS];

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            JobItemCancelledEvent::getEventName() => [
                [
                    'cancelled',
                ],
            ],
            JobItemProcessedEvent::getEventName() => [
                [
                    'processed',
                ],
            ],
            JobItemProcessingEvent::getEventName() => [
                [
                    'processing',
                ],
            ],
            JobItemQueuedEvent::getEventName() => [
                [
                    'queued',
                ],
            ],
            JobItemReceivedEvent::getEventName() => [
                [
                    'received',
                ],
            ],
        ];
    }

    /**
     * @param JobItemCancelledEvent $event
     */
    public function cancelled(JobItemCancelledEvent $event)
    {
        $this->updateJobItemStatus($event, JobConstants::JOB_CANCELLED, true);
    }

    /**
     * @param JobItemProcessedEvent $event
     */
    public function processed(JobItemProcessedEvent $event)
    {
        $this->updateJobItemStatus($event, JobConstants::JOB_PROCESSED, true);
    }

    /**
     * @param JobItemProcessingEvent $event
     */
    public function processing(JobItemProcessingEvent $event)
    {
        $this->updateJobItemStatus($event, JobConstants::JOB_IN_PROGRESS);
    }

    /**
     * @param JobItemQueuedEvent $event
     */
    public function queued(JobItemQueuedEvent $event)
    {
        $this->updateJobItemStatus($event, JobConstants::JOB_QUEUED);
    }

    /**
     * @param JobItemReceivedEvent $event
     */
    public function received(JobItemReceivedEvent $event)
    {
        $this->updateJobItemStatus($event, JobConstants::JOB_RECEIVED);
    }
}
