<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber\Job;

use App\Constants\Transport\JobConstants;
use App\Constants\Transport\Queue;
use App\Entity\Job;
use App\Entity\Manager\JobEntityManager;
use App\Event\AbstractEvent;
use App\Event\Job\JobCompleteEvent;
use App\Event\Job\JobInitiatedEvent;
use App\Event\Job\JobProcessedEvent;
use App\Event\Job\JobPublishFailedEvent;
use App\Event\Job\JobReceivedEvent;
use App\EventSubscriber\AbstractMessageEventSubscriber;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Protocol\MessageFactory;
use App\Service\JobService;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;

class JobProcessorEventSubscriber extends AbstractMessageEventSubscriber
{
    const HEADERS = [
        Queue::SYSTEM_PUBLISHER_HEADER_NAME => Queue::SYSTEM_PUBLISHER_NAME,
        JobConstants::REQUEST_HEADER_NAME => JobConstants::REQUEST_SYNC_ORDER_REQUEST,
    ];

    /**
     * @var ClientPublisher
     */
    private $clientPublisher;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var JobEntityManager
     */
    private $entityManager;

    /**
     * @var JobService
     */
    private $jobService;

    /** @var MessageFactory */
    private $messageFactory;

    /**
     * JobProcessorEventSubscriber constructor.
     *
     * @param ClientPublisher          $clientPublisher
     * @param EventDispatcherInterface $dispatcher
     * @param JobEntityManager         $entityManager
     * @param JobService               $jobService
     * @param LoggerInterface          $logger
     * @param MessageFactory           $messageFactory
     * @param SerializerInterface      $serializer
     */
    public function __construct(
        ClientPublisher $clientPublisher,
        EventDispatcherInterface $dispatcher,
        JobEntityManager $entityManager,
        JobService $jobService,
        LoggerInterface $logger,
        MessageFactory $messageFactory,
        SerializerInterface $serializer
    ) {
        $this->clientPublisher = $clientPublisher;
        $this->dispatcher = $dispatcher;
        $this->entityManager = $entityManager;
        $this->jobService = $jobService;
        $this->messageFactory = $messageFactory;
        parent::__construct($logger, $serializer);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            JobInitiatedEvent::getEventName() => [
                [
                    'jobInitiated',
                ],
            ],
            JobReceivedEvent::getEventName() => [
                [
                    'jobReceived',
                ],
            ],
            JobProcessedEvent::getEventName() => [
                [
                    'jobProcessed',
                ],
            ],
            JobCompleteEvent::getEventName() => [
                [
                    'jobComplete',
                ],
            ],
        ];
    }

    public function jobInitiated(JobInitiatedEvent $event)
    {
        $job = $event->getJob()
            ->setStatus(jobConstants::JOB_INITIATED);

        $this->jobSuccess($event, $job);
    }

    public function jobReceived(JobReceivedEvent $event)
    {
        $job = $event->getJob()
            ->setStatus(JobConstants::JOB_RECEIVED);

        $this->jobSuccess($event, $job);
    }

    public function jobProcessedEvent(JobProcessedEvent $event)
    {
        $job = $event->getJob()
            ->setStatus(JobConstants::JOB_PROCESSED);

        $this->jobSuccess($event, $job);
    }

    public function jobCompleteEvent(JobCompleteEvent $event)
    {
        $job = $event->getJob()
            ->setStatus(JobConstants::JOB_COMPLETE);

        $this->jobSuccess($event, $job);
    }

    public function jobSuccess(AbstractEvent $event, Job $job)
    {
        $this->logger->info($event::getEventName(), [
            'job' => json_decode($this->serializer->serialize($job, self::ENTITY_LOG_FORMAT), true),
            'jobUUID' => $job->getId(),
        ]);

        $this->save($job);
        $this->publish($event, $job);
    }

    private function publish(AbstractEvent $event, Job $job, array $headers = [])
    {
        $headers = array_merge(
            self::HEADERS,
            [Queue::EVENT_NAME_REQUEST_HEADER => $event::getEventName()],
            $headers
        );

        try {
            // Publish job initiated message
            $packet = $this->messageFactory->createPacket(
                Queue::JOB_PERSISTENT_ROUTING_KEY,
                json_encode($job),
                $headers
            );
            $this->clientPublisher->publish($packet);
        } catch (\Exception $e) {
            $this->dispatcher->dispatch(
                new JobPublishFailedEvent($job, $e),
                JobPublishFailedEvent::getEventName()
            );
        }
    }

    private function save(job $job)
    {
        $this->entityManager->persist($job);
        $this->entityManager->flush();
    }
}
