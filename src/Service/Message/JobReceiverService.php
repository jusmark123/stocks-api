<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Message;

use App\Constants\Transport\JobConstants;
use App\Entity\Job;
use App\Event\Job\JobReceivedEvent;
use App\Event\Job\JobReceivedFailedEvent;
use App\Helper\ValidationHelper;
use App\Message\Factory\JobMessageFactory;
use App\Service\JobService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class JobReceiverService.
 */
class JobReceiverService extends ReceiverService
{
    /**
     * @var JobMessageFactory
     */
    protected $jobFactory;

    /**
     * @var JobService
     */
    protected $jobService;

    /**
     * JobReceiverService constructor.
     *
     * @param EntityManagerInterface   $entityManager
     * @param JobMessageFactory        $jobFactory
     * @param JobService               $jobService
     * @param LoggerInterface          $logger
     * @param ValidationHelper         $validator
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        JobMessageFactory $jobFactory,
        JobService $jobService,
        LoggerInterface $logger,
        ValidationHelper $validator,
        EventDispatcherInterface $dispatcher
    ) {
        $this->jobService = $jobService;
        $this->jobFactory = $jobFactory;
        parent::__construct($dispatcher, $entityManager, $logger, $validator);
    }

    /**
     * @param array $message
     *
     * @throws \Throwable
     */
    public function receive(array $message)
    {
        try {
            $this->preReceive('DB:Handler start receiving job message');

            $job = $this->jobFactory->createFromMessage($message);
            $job->setStatus(JobConstants::JOB_RECEIVED_STATUS);

            $this->validator->validate($job);

            $this->dispatcher->dispatch(
                new JobReceivedEvent($job),
                JobReceivedEvent::getEventName()
            );
        } catch (\Throwable $e) {
            $job->setStatus(JobConstants::FAILED_STATUS)
                ->setErrorMessage($e->getMessage())
                ->setErrorTrace($e->getTraceAsString());

            $this->dispatcher->dispatch(
                new JobReceivedFailedEvent($job, $e),
                JobReceivedFailedEvent::getEventName()
            );
            throw $e;
        } finally {
            if ($job instanceof Job) {
                $this->entityManager->persist($job);
            }
            $this->postReceive('DB:Handler end receiving job message');
        }
    }
}
