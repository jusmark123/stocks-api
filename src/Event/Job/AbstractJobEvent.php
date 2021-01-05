<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Entity\Job;
use App\Entity\JobItem;
use App\Event\AbstractEvent;

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
