<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataProvider\Job;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\Exception\ItemNotFoundException;
use App\Constants\Transport\JobConstants;
use App\Entity\Job;
use App\Entity\Manager\JobEntityManager;
use App\Event\Job\JobCancelFailedEvent;
use App\Event\Job\JobCancelledEvent;
use App\Service\JobService;

/**
 * Class CancelJobRequestItemDataProvider.
 */
class CancelJobRequestItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    const RESOURCE_CLASS = Job::class;
    const OPERATION_NAME = 'cancel_job_request';

    /**
     * @var JobEntityManager
     */
    private $entityManager;

    /**
     * @var JobService
     */
    private $jobService;

    /**
     * CancelJobRequestItemDataProvider constructor.
     *
     * @param JobEntityManager $entityManager
     * @param JobService       $jobService
     */
    public function __construct(JobEntityManager $entityManager, JobService $jobService)
    {
        $this->entityManager = $entityManager;
        $this->jobService = $jobService;
    }

    /**
     * @param string      $resourceClass
     * @param string|null $operationName
     * @param array       $context
     *
     * @return bool
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return self::RESOURCE_CLASS === $resourceClass && self::OPERATION_NAME === $operationName;
    }

    /**
     * @param string           $resourceClass
     * @param array|int|string $id
     * @param string|null      $operationName
     * @param array            $context
     *
     * @return object|void|null
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        $job = $this->entityManager->findOneBy(['guid' => $id]);

        if (!$job instanceof Job) {
            throw new ItemNotFoundException('Job not found');
        }

        try {
            $this->jobService->dispatch(new JobCancelledEvent($job));
        } catch (\Exception $e) {
            $this->jobService->dispatch(new JobCancelFailedEvent($e, $job));
        }

        return $job->setStatus(JobConstants::JOB_CANCELLED);
    }
}
