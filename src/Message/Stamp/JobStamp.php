<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

/**
 * Class JobStamp.
 */
class JobStamp implements StampInterface
{
    /**
     * @var string
     */
    private $jobId;

    /**
     * JobStamp constructor.
     *
     * @param string $jobId
     */
    public function __construct(string $jobId)
    {
        $this->jobId = $jobId;
    }

    /**
     * @return string
     */
    public function getJobId(): string
    {
        return $this->jobId;
    }

    /**
     * @param string $jobId
     *
     * @return JobStamp
     */
    public function setJobId(string $jobId): JobStamp
    {
        $this->jobId = $jobId;

        return $this;
    }
}
