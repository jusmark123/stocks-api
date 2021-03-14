<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Entity\Job;
use App\Entity\JobItem;
use App\Message\Job\JobRequestMessageInterface;

/**
 * Traits JobEventTrait.
 */
trait JobEventTrait
{
    /**
     * @var Job|null
     */
    protected ?Job $job = null;

    /**
     * @var JobItem|null
     */
    protected ?JobItem $jobItem = null;

    /**
     * @var JobRequestMessageInterface|null
     */
    protected ?JobRequestMessageInterface $message = null;

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
     * @return ?JobRequestMessageInterface
     */
    public function getJobMessage(): ?JobRequestMessageInterface
    {
        return $this->message;
    }
}
