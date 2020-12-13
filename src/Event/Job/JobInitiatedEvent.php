<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Entity\Job;
use App\Event\AbstractJobEvent;

class JobInitiatedEvent extends AbstractJobEvent
{
    const EVENT_NAME = 'job.initiated';

    /**
     * JobInitiatedEvent Contructor.
     *
     * @param Job $job
     */
    public function __construct(Job $job)
    {
        parent::__construct($job);
    }
}
