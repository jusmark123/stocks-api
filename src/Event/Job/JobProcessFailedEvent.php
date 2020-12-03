<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Entity\Job;
use App\Event\AbstractFailedEvent;

class JobProcessFailedEvent extends AbstractFailedEvent
{
    const EVENT_NAME = 'job.process';

    protected $job;

    public function __construct(Job $job, \Exception $exception)
    {
        $this->job = $job;
        parent::__construct($exception);
    }

    /**
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }
}
