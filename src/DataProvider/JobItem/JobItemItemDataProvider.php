<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataProvider\JobItem;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\JobItem;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;

/**
 * Class JobItemItemDataProvider.
 */
class JobItemItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    const RESOURCE_CLASS = JobItem::class;
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
     * @param string           $resourceClass
     * @param array|int|string $id
     * @param string|null      $operationName
     * @param array            $context
     *
     * @return object|null
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        $jobItem = $this->entityManager->getRepository(JobItem::class)->findOneBy(['guid' => $id]);

        $status = $this->cache->get($jobItem->getGuid()->toString());

        $jobItem->setStatus($status ?? $jobItem->getStatus());

        return $jobItem;
    }
}
