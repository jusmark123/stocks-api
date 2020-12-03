<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Job;

/**
 * Class AbstractJobRequestDataProvider.
 */
abstract class AbstractJobRequestDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    use JobRequestDataProviderTrait;

    protected const RESOURCE_CLASS = '';
    protected const OPERATION_NAME = '';

    /**
     * @return string
     */
    protected function getResourceClass(): string
    {
        return static::RESOURCE_CLASS;
    }

    /**
     * @return string
     */
    protected function getOperationName(): string
    {
        return static::OPERATION_NAME;
    }

    /**
     * @param string           $resourceClass
     * @param array|int|string $id
     * @param string|null      $operationName
     * @param array            $context
     *
     * @return Job
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): Job
    {
        return $this->createJob($resourceClass, $id, $opertaionName = null, $context);
    }
}
