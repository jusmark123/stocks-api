<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber\Job;

use App\Constants\Transport\JobConstants;
use App\Event\JobItem\JobItemProcessFailedEvent;
use App\Event\JobItem\JobItemQueueFailedEvent;
use App\Event\JobItem\JobItemReceiveFailedEvent;

/**
 * Class JobItemFailedEventSubscriber.
 */
class JobItemFailedEventSubscriber extends AbstractJobEventSubscriber
{
    /**
     * @return \string[][][]
     */
    public static function getSubscribedEvents()
    {
        return [
            JobItemProcessFailedEvent::getEventName() => [
                [
                    'process',
                ],
            ],
            JobItemQueueFailedEvent::getEventName() => [
                [
                    'queue',
                ],
            ],
            JobItemReceiveFailedEvent::getEventName() => [
                [
                    'receive',
                ],
            ],
        ];
    }

    /**
     * @param JobItemProcessFailedEvent $event
     */
    public function process(JobItemProcessFailedEvent $event)
    {
        $this->setJobItemErrorData($event);
        $this->updateJobItemStatus($event, JobConstants::JOB_FAILED, true);
    }

    /**
     * @param JobItemQueueFailedEvent $event
     */
    public function queue(JobItemQueueFailedEvent $event)
    {
        $this->setJobItemErrorData($event);
        $this->updateJobItemStatus($event, JobConstants::JOB_FAILED, true);
    }

    /**
     * @param JobItemReceiveFailedEvent $event
     */
    public function receive(JobItemReceiveFailedEvent $event)
    {
        $this->setJobItemErrorData($event);
        $this->updateJobItemStatus($event, JobConstants::JOB_FAILED, true);
    }
}
