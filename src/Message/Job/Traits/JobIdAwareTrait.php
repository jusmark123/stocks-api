<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Job\Traits;

/**
 * Class JobIdAwareTrait.
 */
trait JobIdAwareTrait
{
    /**
     * @var string
     */
    protected $jobId;

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
     * @return $this
     */
    public function setJobId(string $jobId)
    {
        $this->jobId = $jobId;

        return $this;
    }
}
