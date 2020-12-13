<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Entity\Job;
use App\Event\AbstractJobEvent;

class JobCompleteEvent extends AbstractJobEvent
{
    const EVENT_NAME = 'job.complete';

    /**
     * JobCompleteEvent Constructor.
     *
     * @param Job $job
     */
    public function __construct(Job $job)
    {
        parent::__construct($job);
    }
}
