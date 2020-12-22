<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataProvider;

use App\Entity\Job;

/**
 * Trait JobRequestDataProviderTrait.
 */
trait JobRequestDataProviderTrait
{
    /**
     * @param string $resourceClass
     * @param string $operationName
     * @param array  $context
     *
     * @return bool
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $this->getResourceClass() === $resourceClass
            && $this->getOperationName() === $operationName;
    }

    /**
     * @return string
     */
    abstract protected function getResourceClass(): string;

    /**
     * @return string
     */
    abstract protected function getOperationName(): string;

    /**
     * @param string $resourceClass
     * @param $id
     * @param string|null $operationName
     * @param array       $context
     *
     * @return Job
     */
    abstract public function createJob(string $resourceClass, $id, string $operationName = null, array $context = []): Job;
}
