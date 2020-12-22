<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\JobHandler;

use App\Entity\Job;
use App\Entity\JobItem;

/**
 * Interface JobHandlerInterface.
 */
interface JobHandlerInterface
{
    /**
     * @param string      $jobName
     * @param string|null $resourceClass
     *
     * @return mixed
     */
    public function supports(string $jobName, ?string $resourceClass = null): bool;

    /**
     * @param Job $job
     *
     * @return mixed
     */
    public function prepare(Job $job);

    /**
     * @param JobItem $jobItem
     * @param Job     $job
     *
     * @return mixed
     */
    public function execute(JobItem $jobItem, Job $job);

    /**
     * @return string
     */
    public static function getJobName(): string;

    /**
     * @return string
     */
    public static function getJobDescription(): string;
}
