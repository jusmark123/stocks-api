<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Job;

/**
 * Class JobStatusMessage.
 */
class JobStatusMessage
{
    /**
     * @var string
     */
    private $jobId;

    /**
     * @var string
     */
    private $status;

    /**
     * JobItemStatusMessageHandler constructor.
     *
     * @param string $jobId
     * @param string $status
     */
    public function __construct(string $jobId, string $status)
    {
        $this->jobId = $jobId;
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
    public function getStatus(): string
    {
        return $this->status;
    }
}
