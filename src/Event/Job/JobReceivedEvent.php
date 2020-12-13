<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Entity\Job;
use App\Event\AbstractEvent;

/**
 * Class JobReceivedEvent.
 */
class JobReceivedEvent extends AbstractEvent
{
    const EVENT_NAME = 'job.receive';

    /**
     * @var Job
     */
    private $job;

    /**
     * JobReceivedEvent constructor.
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
    public function getJob()
    {
        return $this->job;
    }
}
