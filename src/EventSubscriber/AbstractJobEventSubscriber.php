<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\AbstractJobEvent;
use App\Event\AbstractJobFailedEvent;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\Service\Entity\JobEntityService;
use App\Service\Entity\JobItemEntityService;
use App\Service\JobService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AbstractJobEventSubscriber extends AbstractEventSubscriber
{
    const ENTITY_LOG_FORMAT = 'json';

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
     * @var ClientPublisher
     */
    protected $publisher;

    /**
     * AbstractJobEventSubscriber constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param JobEntityService         $jobEntityService
     * @param JobItemEntityService     $jobDataItemEntityService
     * @param LoggerInterface          $logger
     * @param ClientPublisher          $publisher
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        JobEntityService $jobEntityService,
        JobItemEntityService $jobDataItemEntityService,
        JobService $jobService,
        LoggerInterface $logger,
        ClientPublisher $publisher
    ) {
        $this->dispatcher = $dispatcher;
        $this->jobEntityService = $jobEntityService;
        $this->jobItemEntityService = $jobDataItemEntityService;
        $this->jobService = $jobService;
        $this->logger = $logger;
        $this->publisher = $publisher;
    }

    /**
     * @param AbstractJobEvent|AbstractJobFailedEvent $event
     * @param string                                  $status
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected function updateJobStatus($event, string $status)
    {
        $job = $event->getJob()->setStatus($status);
        $this->jobEntityService->save($job);
    }

    /**
     * @param AbstractJobEvent|AbstractJobFailedEvent $event
     * @param string                                  $status
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected function updateJobItemStatus($event, string $status)
    {
        $jobItem = $event->getJobItem()->setStatus($status);
        $this->jobItemEntityService->save($jobItem);
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

        if (null !== $job) {
            $job->setErrorTrace($exception->getTraceAsString())
                ->setErrorMessage($exception->getMessage());
        }
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
