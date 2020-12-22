<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Constants\Transport\JobConstants;
use App\Entity\Job;
use App\Event\AbstractJobFailedEvent;
use App\Event\Job\JobInitiateFailedEvent;
use App\Event\Job\JobProcessFailedEvent;
use App\Event\Job\JobPublishFailedEvent;
use App\Event\Job\JobReceiveFailedEvent;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

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
    }

    /**
     * @param JobReceiveFailedEvent $event
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function receive(JobReceiveFailedEvent $event)
    {
        $job = $event->getJob();

        if (!$job instanceof Job) {
            $jobMessage = $event->getJobMessage();
            /** @var Job $job */
            $job = $this->jobEntityService
                ->getEntityManager()
                ->getRepository(Job::class)
                ->findOneBy(['guid' => $jobMessage['guid']]);
            $event->setJob($job);
        }

        $this->updateJobStatus($event, JobConstants::JOB_FAILED);
        $this->setJobErrorData($event);
        $this->logError($event);
    }

    /**
     * @param JobProcessFailedEvent $event
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function process(JobProcessFailedEvent $event)
    {
        $job = $event->getJob();
        $this->setJobErrorData($event);
        $this->logError($event);
    }

    /**
     * @param JobPublishFailedEvent $event
     */
    public function publish(JobPublishFailedEvent $event)
    {
        $this->setJobErrorData($event);
        $this->logError($event);
    }

    /**
     * @param AbstractJobFailedEvent $event
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected function setJobErrorData(AbstractJobFailedEvent $event): void
    {
        $exception = $event->getException();
        $job = $event->getJob()
            ->setErrorMessage($exception->getMessage())
            ->setErrorTrace($exception->getTraceAsString())
            ->setStatus(JobConstants::JOB_FAILED);
        $this->jobEntityService->save($job);
    }
}
