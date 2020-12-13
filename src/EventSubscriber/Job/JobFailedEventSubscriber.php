<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber\Job;

use App\Constants\Transport\JobConstants;
use App\Entity\Job;
use App\Event\AbstractFailedEvent;
use App\Event\Job\JobInitiateFailedEvent;
use App\Event\Job\JobProcessFailedEvent;
use App\Event\Job\JobPublishFailedEvent;
use App\Event\Job\JobReceiveFailedEvent;
use App\EventSubscriber\AbstractMessageEventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class JobFailedEventSubscriber.
 */
class JobFailedEventSubscriber extends AbstractMessageEventSubscriber
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * JobFailedEventSubscriber constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface        $logger
     * @param SerializerInterface    $serializer
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        SerializerInterface $serializer
    ) {
        $this->entityManager = $entityManager;
        parent::__construct($logger, $serializer);
    }

    /**
     * @return array
     */
    public function getEventSubscribedEvents()
    {
        return [
            JobInitiateFailedEvent::getEventName() => [
                [
                    'jobInitiateFailed',
                ],
            ],
            JobReceiveFailedEvent::getEventName() => [
                [
                    'jobReceiveFailed',
                ],
            ],
            JobProcessFailedEvent::getEventName() => [
                [
                    'jobProcessFailed',
                ],
            ],
            JobPublishFailedEvent::getEventName() => [
                [
                    'jobPublishFailed',
                ],
            ],
        ];
    }

    /**
     * @param JobReceiveFailedEvent $event
     */
    public function jobReceiveFailed(JobReceiveFailedEvent $event)
    {
        $job = $event->getJob();
        $this->setJobErrorData($event, $job);
        $this->createLogEntry($event, $job);
    }

    /**
     * @param JobProcessFailedEvent $event
     */
    public function jobProcessFailed(JobProcessFailedEvent $event)
    {
        $job = $event->getJob();
        $this->setJobErrorData($event, $job);
        $this->createLogEntry($event, $job);
    }

    /**
     * @param JobPublishFailedEvent $event
     */
    public function jobPublishFailed(JobPublishFailedEvent $event)
    {
        $job = $event->getJob();
        $this->setJobErrorData($event, $job);
        $this->createLogEntry($event, $job);
    }

    /**
     * @param AbstractFailedEvent $event
     * @param Job                 $job
     */
    private function setJobErrorData(AbstractFailedEvent $event, Job $job): void
    {
        $exception = $event->getException();
        $job
            ->setErrorMessage($exception->getMessage())
            ->setErrorTrace($exception->getTraceAsString())
            ->setStatus(JobConstants::JOB_FAILED);

        $this->entityManager->persist($job);
        $this->entityManager->flush();
    }

    /**
     * @param AbstractFailedEvent $event
     * @param Job                 $job
     */
    private function createLogEntry(AbstractFailedEvent $event, Job $job)
    {
        $this->logger->error($event->getException()->getMessage(), [
            'exception' => $event->getException(),
            'job' => json_decode($this->serializer->serialize($job, self::ENTITY_LOG_FORMAT), true),
            'jobUUID' => $job ? $job->getId() : null,
        ]);
    }
}
