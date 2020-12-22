<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber\Job;

use App\Constants\Transport\JobConstants;
use App\Constants\Transport\Queue;
use App\Entity\Job;
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

    /** @var ClientPublisher */
    private $clientPublisher;

    /** @var JobService */
    private $jobService;

    /** @var MessageFactory */
    private $messageFactory;

    /**
     * JobProcessorEventSubscriber constructor.
     *
     * @param ClientPublisher          $clientPublisher
     * @param EventDispatcherInterface $dispatcher
     * @param JobService               $jobService
     * @param LoggerInterface          $logger
     * @param MessageFactory           $messageFactory
     * @param SerializerInterface      $serializer
     */
    public function __construct(
        ClientPublisher $clientPublisher,
        EventDispatcherInterface $dispatcher,
        JobService $jobService,
        LoggerInterface $logger,
        MessageFactory $messageFactory,
        SerializerInterface $serializer
    ) {
        $this->clientPublisher = $clientPublisher;
        $this->jobService = $jobService;
        $this->messageFactory = $messageFactory;
        parent::__construct($dispatcher, $logger, $serializer);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
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
            JobProcessedEvent::getEventName() => [
                [
                    'complete',
                ],
            ],
        ];
    }

    /**
     * @param JobInitiatedEvent $event
     */
    public function initiated(JobInitiatedEvent $event)
    {
        $this->publish($event);
    }

    /**
     * @param JobReceivedEvent $event
     */
    public function inProgress(JobReceivedEvent $event)
    {
        $job = $event->getJob()
            ->setStatus(JobConstants::JOB_IN_PROGRESS);
        $this->jobSuccess($event);
    }

    /**
     * @param JobCompleteEvent $event
     */
    public function complete(JobCompleteEvent $event)
    {
        $job = $event->getJob()
            ->setStatus(JobConstants::JOB_COMPLETE);
        $this->jobSuccess($event);
    }

    /**
     * @param AbstractEvent $event
     */
    public function jobSuccess(AbstractEvent $event)
    {
        $this->jobService->save($job);
        $this->publish($event, $job);
    }

    /**
     * @param AbstractEvent $event
     * @param array         $headers
     */
    private function publish(AbstractEvent $event, array $headers = [])
    {
        $headers = array_merge(self::HEADERS,
            [Queue::EVENT_NAME_REQUEST_HEADER => $event::getEventName()],
            $headers
        );

        $job = $event->getJob();

        $this->logger->info(sprintf('Job %s %s', $job->getId(), $job->getStatus()));

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
}
