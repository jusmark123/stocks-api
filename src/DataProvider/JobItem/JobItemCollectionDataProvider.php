<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataProvider\JobItem;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Job;
use App\Entity\JobItem;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;

class JobItemCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    const RESOURCE_CLASS = Job::class;
    const OPERATION_NAME = 'get';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Client
     */
    private $cache;

    /**
     * JobItemItemDataProvider constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param Client                 $redis
     */
    public function __construct(EntityManagerInterface $entityManager, Client $redis)
    {
        $this->cache = $redis;
        $this->entityManager = $entityManager;
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
     * @return iterable|object[]
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        /** @var JobItem[] $jobs */
        $jobItems = $this->entityManager->getRepository(JobItem::class)->findAll();

        foreach ($jobItems as $jobItem) {
            $jobItem->setStatus($status ?? $jobItem->getStatus());
        }

        return $jobItems;
    }
}
