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
trait JobEventTrait
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
     * @return array|null
     */
    public function getJobMessage(): array
    {
        return $this->jobMessage;
    }
}
