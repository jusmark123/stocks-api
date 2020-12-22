<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataProvider\Job;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Job;
use App\Service\JobService;
use Doctrine\ORM\EntityManagerInterface;

class JobCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    const RESOURCE_CLASS = Job::class;
    const OPERATION_NAME = 'get';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var JobService
     */
    private $jobService;

    /**
     * JobCollectionDataProvider constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param JobService             $jobService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        JobService $jobService
    ) {
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
     * @param string      $resourceClass
     * @param string|null $operationName
     * @param array       $context
     *
     * @return iterable|void
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $jobs = $this->entityManager
            ->getRepository(self::RESOURCE_CLASS)
            ->findAll();

        foreach ($jobs as $job) {
            $this->jobService->calculateJobPercentageComplete($job);
        }

        return $jobs;
    }
}
