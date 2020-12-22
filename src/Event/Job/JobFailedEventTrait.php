<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Entity\Job;
use App\Entity\JobItem;

/**
 * Trait JobEventTrait.
 */
trait JobFailedEventTrait
{
    /**
     * @var Job|null
     */
    protected $job;

    /**
     * @var JobItem|null
     */
    protected $jobItem;

    /**
     * @var array|null
     */
    protected $jobMessage;

    /**
     * @return Job|null
     */
    public function getJob(): ?Job
    {
        return $this->job;
    }

    /**
     * @return JobItem|null
     */
    public function getJobItem(): ?JobItem
    {
        return $this->jobItem;
    }

    /**
     * @return array
     */
    public function getJobMessage(): array
    {
        return $this->jobMessage;
    }
}
