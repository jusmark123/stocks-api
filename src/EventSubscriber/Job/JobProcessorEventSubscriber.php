<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber\Job;

use App\Constants\Transport\JobConstants;
use App\Event\AbstractEvent;
use App\Event\Job\JobCompleteEvent;
use App\Event\Job\JobCreatedEvent;
use App\Event\Job\JobInitiatedEvent;
use App\Event\Job\JobProcessedEvent;
use App\Event\Job\JobPublishFailedEvent;
use App\Event\Job\JobReceivedEvent;
use App\EventSubscriber\AbstractMessageEventSubscriber;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Exception\InvalidMessage;
use App\MessageClient\Protocol\MessageFactory;
use App\Service\JobService;
use Doctrine\Migrations\Configuration\Migration\Exception\JsonNotValid;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class JobProcessorEventSubscriber.
 */
class JobProcessorEventSubscriber extends AbstractMessageEventSubscriber
{
    /** @var JobService */
    private $jobService;

    /**
     * JobProcessorEventSubscriber constructor.
     *
     * @param ClientPublisher          $publisher
     * @param EventDispatcherInterface $dispatcher
     * @param JobService               $jobService
     * @param LoggerInterface          $logger
     * @param MessageFactory           $messageFactory
     * @param SerializerInterface      $serializer
     */
    public function __construct(
        ClientPublisher $publisher,
        EventDispatcherInterface $dispatcher,
        JobService $jobService,
        LoggerInterface $logger,
        MessageFactory $messageFactory,
        SerializerInterface $serializer
    ) {
        $this->jobService = $jobService;
        parent::__construct($dispatcher, $logger, $messageFactory, $publisher, $serializer);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            JobCreatedEvent::getEventName() => [
                [
                    'created',
                ],
            ],
            JobProcessedEvent::getEventName() => [
                [
                    'complete',
                ],
            ],
            JobInitiatedEvent::getEventName() => [
                [
                    'initiated',
                ],
            ],
            JobReceivedEvent::getEventName() => [
                [
                    'inProgress',
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
     * @param JobCompleteEvent $event
     *
     * @throws InvalidMessage
     */
    public function complete(JobCompleteEvent $event)
    {
        $this->updateJobStatus($event, JobConstants::JOB_COMPLETE);
    }

    /**
     * @param JobCreatedEvent $event
     *
     * @throws InvalidMessage
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \App\MessageClient\Exception\PublishException
     */
    public function created(JobCreatedEvent $event)
    {
        try {
            $job = $event->getJob();
            $packet = $this->messageFactory->createPacket(
                JobConstants::JOB_REQUEST_ROUTING_KEY,
                serialize($job),
                $this->jobService->getHeaders([
                    JobConstants::REQUEST_HEADER_NAME => JobConstants::REQUEST_SYNC_ORDER_REQUEST,
                ])
            );

            if (json_last_error()) {
                throw new JsonNotValid(sprintf('The json is not valid for job: %s', json_last_error_msg()));
            }

            $this->publish($packet);
            $this->updateJobStatus($event, JobConstants::JOB_QUEUED);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), [
                'exception' => $e,
                'jobId' => $job->getGuid()->toString(),
            ]);
            $this->dispatch(new JobPublishFailedEvent($job, $e));
        }
    }

    /**
     * @param JobInitiatedEvent $event
     *
     * @throws InvalidMessage
     */
    public function initiated(JobInitiatedEvent $event)
    {
        $this->updateJobStatus($event, JobConstants::JOB_INITIATED);
    }

    /**
     * @param JobReceivedEvent $event
     *
     * @throws InvalidMessage
     */
    public function inProgress(JobReceivedEvent $event)
    {
        $this->updateJobStatus($event, JobConstants::JOB_IN_PROGRESS);
    }

    /**
     * @param AbstractEvent $event
     *
     * @throws InvalidMessage
     */
    public function received(AbstractEvent $event)
    {
        $this->updateJobStatus($event, JobConstants::JOB_INITIATED);
    }

    /**
     * @param $event
     * @param $status
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function updateJobStatus($event, $status)
    {
        $job = $event->getJob()
            ->setStatus($status);

        $this->jobService->save($job);

        $this->logger->info(sprintf('Job %s %s', $job->getId(), $job->getStatus()));

        $this->publishJob($job);
    }

    /**
     * @param $job
     */
    private function publishJob($job)
    {
        try {
            $packet = $this->messageFactory->createPacket(
                JobConstants::JOB_INFO_ROUTING_KEY,
                [
                    'jobId' => $job->getGuid()->toString(),
                    'status' => $job->getStatus(),
                ],
                $this->jobService->getHeaders()
            );

            $this->publish($packet);
        } catch (\Exception $e) {
            $this->dispatch(new JobPublishFailedEvent($job, $e));
        }
    }
}
