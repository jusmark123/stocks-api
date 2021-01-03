<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber\Job;

use App\Constants\Transport\JobConstants;
use App\Event\Job\JobCancelledEvent;
use App\Event\Job\JobCompleteEvent;
use App\Event\Job\JobCreatedEvent;
use App\Event\Job\JobIncompleteEvent;
use App\Event\Job\JobInitiatedEvent;
use App\Event\Job\JobInProgressEvent;
use App\Event\Job\JobProcessedEvent;
use App\Event\Job\JobProcessingEvent;
use App\Event\Job\JobReceivedEvent;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class JobEventSubscriber.
 */
class JobEventSubscriber extends AbstractJobEventSubscriber
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            JobCancelledEvent::getEventName() => [
                [
                    'cancelled',
                ],
            ],
            JobCompleteEvent::getEventName() => [
                [
                    'complete',
                ],
            ],
            JobCreatedEvent::getEventName() => [
                [
                    'created',
                ],
            ],
            JobIncompleteEvent::getEventName() => [
                [
                    'inComplete',
                ],
            ],
            JobInitiatedEvent::getEventName() => [
                [
                    'initiated',
                ],
            ],
            JobInProgressEvent::getEventName() => [
                [
                    'inProgress',
                ],
            ],
            JobProcessedEvent::getEventName() => [
                [
                    'processed',
                ],
            ],
            JobProcessingEvent::getEventName() => [
                [
                    'processing',
                ],
            ],
            JobReceivedEvent::getEventName() => [
                [
                    'received',
                ],
            ],
        ];
    }

    /**
     * @param JobCancelledEvent $event
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function cancelled(JobCancelledEvent $event)
    {
        $this->updateJobStatus($event, JobConstants::JOB_CANCELLED);
    }

    /**
     * @param JobCompleteEvent $event
     */
    public function complete(JobCompleteEvent $event)
    {
        if ($this->jobService->hasFailedJobs($event->getJob())) {
            $this->dispatch(new JobIncompleteEvent($event->getJob()));
        } else {
            $this->updateJobStatus($event, JobConstants::JOB_COMPLETE);
        }
    }

    /**
     * @param JobCreatedEvent $event
     */
    public function created(JobCreatedEvent $event)
    {
        $this->updateJobStatus($event, JobConstants::JOB_PENDING);
    }

    /**
     * @param JobIncompleteEvent $event
     */
    public function inComplete(JobIncompleteEvent $event)
    {
        $this->updateJobStatus($event, JobConstants::JOB_INCOMPLETE);
    }

    /**
     * @param JobInitiatedEvent $event
     */
    public function initiated(JobInitiatedEvent $event)
    {
        $this->updateJobStatus($event, JobConstants::JOB_INITIATED);
    }

    /**
     * @param JobInProgressEvent $event
     */
    public function inProgress(JobInProgressEvent $event)
    {
        $this->updateJobStatus($event, JobConstants::JOB_IN_PROGRESS);
    }

    /**
     * @param JobProcessingEvent $event
     */
    public function processing(JobProcessingEvent $event)
    {
        $this->updateJobStatus($event, JobConstants::JOB_PROCESSING);
    }

    /**
     * @param JobProcessedEvent $event
     */
    public function processed(JobProcessedEvent $event)
    {
        $this->updateJobStatus($event, JobConstants::JOB_PROCESSED);
    }

    /**
     * @param JobReceivedEvent $event
     */
    public function received(JobReceivedEvent $event)
    {
        $this->updateJobStatus($event, JobConstants::JOB_RECEIVED);
    }
}
