<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Constants\Transport\JobConstants;
use App\Event\AbstractJobFailedEvent;
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
    public static function getEventSubscribedEvents()
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
     * @param AbstractJobFailedEvent $event
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function process(AbstractJobFailedEvent $event)
    {
        $this->setErrorData($event);
        $this->updateJobItemStatus($event, JobConstants::JOB_FAILED);
    }

    /**
     * @param JobItemQueueFailedEvent $event
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function queue(JobItemQueueFailedEvent $event)
    {
        $this->setErrorData($event);
        $this->updateJobItemStatus($event, JobConstants::JOB_FAILED);
    }

    /**
     * @param JobItemReceiveFailedEvent $event
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function receive(JobItemReceiveFailedEvent $event)
    {
        $this->setErrorData($event);
        $this->updateJobItemStatus($event, JobConstants::JOB_FAILED);
    }

    /**
     * @param AbstractJobFailedEvent $event
     */
    protected function setJobItemErrorData(AbstractJobFailedEvent $event)
    {
        $exception = $event->getException();
        $jobItem = $event->getJobItem();

        if (null !== $jobItem) {
            $jobItem->setErrorTrace($exception->getTraceAsString())
                ->setErrorMessage($exception->getMessage());
        }
    }
}
