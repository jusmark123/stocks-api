<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Entity\Job;
use App\Event\AbstractEvent;

/**
 * Class JobProcessedEvent.
 */
class JobProcessedEvent extends AbstractEvent
{
    const EVENT_NAME = 'job.processed';

    /**
     * @var Job
     */
    protected $job;

    /**
     * JobProcessedEvent constructor.
     *
     * @param Job $job
     */
    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    /**
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }
}
