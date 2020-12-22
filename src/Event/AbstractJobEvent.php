<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event;

use App\Entity\Job;
use App\Entity\JobItem;
use App\Event\Job\JobEventTrait;

/**
 * Class AbstractJobEvent.
 */
class AbstractJobEvent extends AbstractEvent
{
    use JobEventTrait;

    /**
     * AbstractJobEvent constructor.
     *
     * @param JobItem $jobItem
     */
    public function __construct(Job $job, ?JobItem $jobItem = null)
    {
        $this->job = $job;
        $this->jobItem = $jobItem;
    }
}
