<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber\Job;

use App\Constants\Transport\JobConstants;
use App\Event\Job\JobInitiateFailedEvent;
use App\Event\Job\JobProcessFailedEvent;
use App\Event\Job\JobPublishFailedEvent;
use App\Event\Job\JobReceiveFailedEvent;

/**
 * Class JobFailedEventSubscriber.
 */
class JobFailedEventSubscriber extends AbstractJobEventSubscriber
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            JobInitiateFailedEvent::getEventName() => [
                [
                    'initiate',
                ],
            ],
            JobReceiveFailedEvent::getEventName() => [
                [
                    'receive',
                ],
            ],
            JobProcessFailedEvent::getEventName() => [
                [
                    'process',
                ],
            ],
            JobPublishFailedEvent::getEventName() => [
                [
                    'publish',
                ],
            ],
        ];
    }

    public function initiate(JobInitiateFailedEvent $event)
    {
        $this->setJobErrorData($event);
        $this->updateJobStatus($event, JobConstants::JOB_FAILED);
    }

    /**
     * @param JobReceiveFailedEvent $event
     */
    public function receive(JobReceiveFailedEvent $event)
    {
        $this->setJobErrorData($event);
        $this->updateJobStatus($event, JobConstants::JOB_FAILED);
    }

    /**
     * @param JobProcessFailedEvent $event
     */
    public function process(JobProcessFailedEvent $event)
    {
        $this->setJobErrorData($event);
        $this->updateJobStatus($event, JobConstants::JOB_FAILED);
    }

    /**
     * @param JobPublishFailedEvent $event
     */
    public function publish(JobPublishFailedEvent $event)
    {
        $this->setJobErrorData($event);
        $this->updateJobStatus($event, JobConstants::JOB_FAILED);
    }
}
