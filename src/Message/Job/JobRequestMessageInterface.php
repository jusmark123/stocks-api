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
     * @return string|null
     */
    public function getJobId(): ?string;

    /**
     * @param string|null $jobId
     *
     * @return $this
     */
    public function setJobId(?string $jobId = null);

    /**
     * @return string
     */
    public function getJobName(): string;

    /**
     * @return string
     */
    public function getJobDescription(): string;

    /**
     * @return JobRequestInterface
     */
    public function getRequest();
}
