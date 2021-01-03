<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber\Job;

use App\Constants\Transport\JobConstants;
use App\Entity\Job;
use App\Entity\JobItem;
use App\Event\Job\AbstractJobEvent;
use App\Event\Job\AbstractJobFailedEvent;
use App\Event\JobItem\AbstractJobItemFailedEvent;
use App\EventSubscriber\AbstractEventSubscriber;
use App\Message\Job\JobItemStatusMessage;
use App\Message\Job\JobStatusMessage;
use App\Service\Entity\JobEntityService;
use App\Service\Entity\JobItemEntityService;
use App\Service\JobService;
use Doctrine\DBAL\Exception;
use Predis\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class AbstractJobEventSubscriber.
 */
class AbstractJobEventSubscriber extends AbstractEventSubscriber
{
    const ENTITY_LOG_FORMAT = 'json';

    /**
     * @var Client
     */
    protected $cache;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var JobEntityService
     */
    protected $jobEntityService;

    /**
     * @var JobItemEntityService
     */
    protected $jobItemEntityService;

    /**
     * @var JobService
     */
    protected $jobService;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * AbstractJobEventSubscriber constructor.
     *
     * @param Client                   $redis
     * @param EventDispatcherInterface $dispatcher
     * @param JobEntityService         $jobEntityService
     * @param JobItemEntityService     $jobDataItemEntityService
     * @param JobService               $jobService
     * @param LoggerInterface          $logger
     * @param MessageBusInterface      $messageBus
     */
    public function __construct(
        Client $redis,
        EventDispatcherInterface $dispatcher,
        JobEntityService $jobEntityService,
        JobItemEntityService $jobDataItemEntityService,
        JobService $jobService,
        LoggerInterface $logger,
        MessageBusInterface $messageBus
    ) {
        $this->cache = $redis;
        $this->dispatcher = $dispatcher;
        $this->jobEntityService = $jobEntityService;
        $this->jobItemEntityService = $jobDataItemEntityService;
        $this->jobService = $jobService;
        $this->logger = $logger;
        $this->messageBus = $messageBus;
    }

    /**
     * @param AbstractJobEvent|AbstractJobFailedEvent $event
     * @param string                                  $status
     */
    protected function updateJobStatus($event, string $status)
    {
        if ($event instanceof AbstractJobFailedEvent && $event->getException() instanceof Exception) {
            $this->jobEntityService->checkConnection();
        }

        $job = $event->getJob()->setStatus($status);
        $this->jobEntityService->save($job);

        $envelope = (new Envelope(new JobStatusMessage($job->getGuid()->toString(), $status)))
            ->with(new AmqpStamp(sprintf(JobConstants::JOB_STATUS_ROUTING_KEY, $job->getGuid()->toString()),
                    AMQP_AUTOACK));

        $this->messageBus->dispatch($envelope);
    }

    /**
     * @param AbstractJobEvent|AbstractJobFailedEvent $event
     * @param string                                  $status
     * @param bool                                    $save
     */
    protected function updateJobItemStatus($event, string $status, $save = false)
    {
        if ($event instanceof AbstractJobFailedEvent && $event->getException() instanceof Exception) {
            $this->jobItemEntityService->checkConnection();
        }

        $jobItem = $event->getJobItem();
        $job = $event->getJob();

        $this->cache->setex(
            sprintf('%s:%s', $job->getGuid()->toString(), $jobItem->getGuid()->toString()),
            1500,
            $status
        );

        if ($save) {
            $jobItem->setStatus($status);
            $this->jobItemEntityService->save($jobItem);
        }

        $envelope = (new Envelope(new JobItemStatusMessage(
                $job->getGuid()->toString(),
                $jobItem->getGuid()->toString(),
                $status))
        )->with(new AmqpStamp(sprintf(JobConstants::JOB_STATUS_ROUTING_KEY, $job->getGuid()->toString()),
                AMQP_AUTOACK));

        $this->messageBus->dispatch($envelope);
    }

    /**
     * @param AbstractJobEvent|AbstractJobFailedEvent $event
     */
    protected function dispatch($event)
    {
        $this->dispatcher->dispatch($event, $event::getEventName());
    }

    /**
     * @param AbstractJobFailedEvent $event
     */
    protected function setJobErrorData(AbstractJobFailedEvent $event)
    {
        $exception = $event->getException();
        $job = $event->getJob();

        if ($job instanceof Job) {
            $job->setErrorTrace($exception->getTraceAsString())
                ->setErrorMessage($exception->getMessage());
        }

        $this->logError($event);
    }

    /**
     * @param AbstractJobItemFailedEvent $event
     */
    protected function setJobItemErrorData(AbstractJobItemFailedEvent $event)
    {
        $exception = $event->getException();
        $jobItem = $event->getJobItem();

        if ($jobItem instanceof JobItem) {
            $jobItem->setErrorTrace($exception->getTraceAsString())
                ->setErrorMessage($exception->getMessage());
        }

        $this->logError($event);
    }

    /**
     * @param AbstractJobFailedEvent $event
     */
    protected function logError(AbstractJobFailedEvent $event)
    {
        $job = $event->getJob();
        $jobItem = $event->getJobItem();

        $this->logger->error($event->getException()->getMessage(), [
            'exception' => $event->getException(),
            'jobUUID' => $job->getGuid()->toString(),
            'jobItemUUID' => null === $jobItem ? $jobItem : $jobItem->getGuid()->toString(),
        ]);
    }
}
