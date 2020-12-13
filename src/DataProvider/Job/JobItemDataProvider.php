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
use App\Service\Entity\JobEntityService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\ItemInterface;

class JobItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    const RESOURCE_CLASS = Job::class;
    const OPERATION_NAME = 'get';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var JobEntityService
     */
    private $jobService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * JobItemDataProvider constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param JobEntityService       $jobService
     * @param LoggerInterface        $logger
     * @param TagAwareCacheInterface $jobCache
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        JobEntityService $jobService,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->jobService = $jobService;
        $this->logger = $logger;
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
        $job = $this->entityManager->getRepository(Job::class)->findOneBy(['guid' => $id]);

        if (!$job instanceof Job) {
            throw new ItemNotFoundException(JobConstants::JOB_NOT_FOUND);
        }
        $cacheKey = $this->jobService->getCacheKey($job);

        $data = $this->jobCache->get($cacheKey, function (ItemInterface $item) {
            $item->tag(['job']);

            return [];
        });

        $this->logger->debug(self::class, ['cacheKey' => $cacheKey, 'data' => $data]);

        return $job->setData($data);
    }
}
