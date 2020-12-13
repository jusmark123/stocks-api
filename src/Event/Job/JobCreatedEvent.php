<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Entity\Job;
use App\Event\AbstractEvent;

class JobCreatedEvent extends AbstractEvent
{
    const EVENT_NAME = 'job.created';

    /**
     * @var Job
     */
    private $job;

    /**
     * JobCreatedEvent constructor.
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
