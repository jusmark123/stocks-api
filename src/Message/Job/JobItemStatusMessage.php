<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Job;

/**
 * Class JobItemStatusMessageHandler.
 */
class JobItemStatusMessage
{
    /**
     * @var string
     */
    private $jobId;

    /**
     * @var string
     */
    private $jobItemId;

    /**
     * @var string
     */
    private $status;

    /**
     * JobItemStatusMessageHandler constructor.
     *
     * @param string $jobId
     * @param string $jobItemId
     * @param string $status
     */
    public function __construct(string $jobId, string $jobItemId, string $status)
    {
        $this->jobId = $jobId;
        $this->jobItemId = $jobItemId;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getJobId(): string
    {
        return $this->jobId;
    }

    /**
     * @return string
     */
    public function getJobItemId(): string
    {
        return $this->jobItemId;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}
