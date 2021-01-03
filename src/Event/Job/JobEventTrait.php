<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Entity\Job;
use App\Entity\JobItem;
use App\Message\JobRequestMessageInterface;

/**
 * Traits JobEventTrait.
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
     * @return JobRequestMessageInterface
     */
    public function getJobMessage(): JobRequestMessageInterface
    {
        return $this->message;
    }
}
