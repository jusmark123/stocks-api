<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Job;

/**
 * Interface JobRequestMessageInterface.
 */
interface JobRequestMessageInterface
{
    /**
     * @return string
     */
    public function getJobId(): string;

    /**
     * @param string $jobId
     *
     * @return $this
     */
    public function setJobId(string $jobId);

    /**
     * @return string
     */
    public function getJobName(): string;

    /**
     * @return string
     */
    public function getJobDescription(): string;
}
