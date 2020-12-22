<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\JobHandler;

use App\Entity\Job;
use App\Entity\JobItem;
use App\Event\JobItem\JobItemProcessingEvent;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Protocol\MessageFactory;
use App\Service\JobService;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class AbstractJobHandler.
 */
abstract class AbstractJobHandler implements JobHandlerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected const JOB_NAME = '';
    protected const JOB_DESCRIPTION = '';

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * @var ClientPublisher
     */
    protected $publisher;

    /**
     * @var JobService
     */
    protected $jobService;

    /**
     * @var Job
     */
    protected $job;

    /**
     * AbstractJobHandler constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param JobService               $jobService
     * @param LoggerInterface          $logger
     * @param MessageFactory           $messageFactory
     * @param ClientPublisher          $publisher
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        JobService $jobService,
        LoggerInterface $logger,
        MessageFactory $messageFactory,
        ClientPublisher $publisher
    ) {
        $this->dispatcher = $dispatcher;
        $this->jobService = $jobService;
        $this->logger = $logger;
        $this->messageFactory = $messageFactory;
        $this->publisher = $publisher;
    }

    /**
     * @return string
     */
    public static function getJobName(): string
    {
        return self::JOB_NAME;
    }

    /**
     * @return string
     */
    public static function getJobDescription(): string
    {
        return self::JOB_DESCRIPTION;
    }

    /**
     * @param JobItem $jobItem
     */
    public function jobItemProcessing(JobItem $jobItem)
    {
        $this->dispatcher->dispatch(
            new JobItemProcessingEvent($jobItem),
            JobItemProcessingEvent::getEventName()
        );
    }
}
