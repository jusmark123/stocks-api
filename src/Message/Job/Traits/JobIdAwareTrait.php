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
     * @var string|null
     */
    protected $jobId;

    /**
     * @return string
     */
    public function getJobId(): ?string
    {
        return $this->jobId;
    }

    /**
     * @param string|null $jobId
     *
     * @return $this
     */
    public function setJobId(?string $jobId = null)
    {
        $this->jobId = $jobId;

        return $this;
    }
}
