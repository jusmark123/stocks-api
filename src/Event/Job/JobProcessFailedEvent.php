<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Entity\Job;
use App\Event\AbstractJobFailedEvent;

class JobProcessFailedEvent extends AbstractJobFailedEvent
{
    const EVENT_NAME = 'job.process';

    /**
     * JobProcessFailedEvent constructor.
     *
     * @param Job        $job
     * @param \Exception $exception
     */
    public function __construct(Job $job, \Exception $exception)
    {
        parent::__construct($job, $exception);
    }
}
