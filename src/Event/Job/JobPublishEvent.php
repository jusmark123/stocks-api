<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Entity\Job;
use App\Event\AbstractEvent;

/**
 * Class JobPublishEvent.
 */
class JobPublishEvent extends AbstractEvent
{
    const EVENT_NAME = 'job.publish';

    /**
     * @var Job
     */
    private $job;

    /**
     * JobPublishEvent constructor.
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
