<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Constants\Transport\JobConstants;
use App\Event\Job\JobCompleteEvent;
use App\Event\Job\JobInProgressEvent;
use App\Event\JobItem\JobItemCancelledEvent;
use App\Event\JobItem\JobItemProcessedEvent;
use App\Event\JobItem\JobItemProcessingEvent;
use App\Event\JobItem\JobItemQueuedEvent;
use App\Event\JobItem\JobItemReceivedEvent;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

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
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function cancelled(JobItemCancelledEvent $event)
    {
        $this->updateJobItemStatus($event, JobConstants::JOB_CANCELLED);
    }

    /**
     * @param JobItemProcessedEvent $event
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function processed(JobItemProcessedEvent $event)
    {
        $this->updateJobItemStatus($event, JobConstants::JOB_PROCESSED);

        if ($this->jobService->isComplete($event->getJob())) {
            $this->dispatcher->dispatch(new JobCompleteEvent($event->getJob()));
        }
    }

    /**
     * @param JobItemProcessingEvent $event
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function processing(JobItemProcessingEvent $event)
    {
        $job = $event->getJob();
        $jobItem = $event->getJobItem();
        if (JobConstants::JOB_IN_PROGRESS === $job->getStatus()) {
            $this->dispatch(new JobInProgressEvent($job, $jobItem));
        }

        $this->updateJobItemStatus($event, JobConstants::JOB_IN_PROGRESS);
    }

    /**
     * @param JobItemQueuedEvent $event
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function queued(JobItemQueuedEvent $event)
    {
        $this->updateJobItemStatus($event, JobConstants::JOB_QUEUED);
    }

    /**
     * @param JobItemReceivedEvent $event
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function received(JobItemReceivedEvent $event)
    {
        $this->updateJobItemStatus($event, JobConstants::JOB_RECEIVED);
    }
}
