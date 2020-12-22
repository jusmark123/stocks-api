<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Message;

use App\Entity\Job;
use App\Helper\ValidationHelper;
use App\Service\JobService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class JobInitiatedService.
 */
class JobInitiatedService extends AbstractMessageService
{
    /**
     * @var JobService
     */
    protected $jobService;

    /**
     * JobInitiatedService constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param EntityManagerInterface   $entityManager
     * @param JobService               $jobService
     * @param LoggerInterface          $logger
     * @param ValidationHelper         $validator
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        EntityManagerInterface $entityManager,
        JobService $jobService,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        $this->jobService = $jobService;
        parent::__construct($dispatcher, $entityManager, $logger, $validator);
    }

    /**
     * @return JobService
     */
    public function getJobService(): JobService
    {
        return $this->jobService;
    }

    /**
     * @param Job $job
     */
    public function initiate(Job $job)
    {
        echo '';
    }
}
