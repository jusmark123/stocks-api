<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Entity\Job;
use App\Event\AbstractEvent;

class JobInitiatedEvent extends AbstractEvent
{
    const EVENT_NAME = 'job.initiated';

    /**
     * @var Job
     */
    protected $job;

    /**
     * JobInitiatedEvent Contructor.
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
