<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\JobHandler;

use App\Entity\Job;

/**
 * Interface JobHandlerProviderInterface.
 */
interface JobHandlerProviderInterface
{
    /**
     * @param array $jobHandlers
     *
     * @return JobHandlerProviderInterface
     */
    public function setJobHandlers(array $jobHandlers): JobHandlerProviderInterface;

    /**
     * @param Job         $job
     * @param string|null $resourceClass
     *
     * @return JobHandlerInterface
     */
    public function getJobHandler(Job $job, ?string $resourceClass = null): JobHandlerInterface;
}
