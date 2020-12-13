<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Entity\Job;
use App\Event\AbstractJobFailedEvent;

/**
 * Class JobIncompleteEvent.
 */
class JobIncompleteEvent extends AbstractJobFailedEvent
{
    const EVENT_NAME = 'job.incomplete';

    /**
     * JobIncompleteEvent constructor.
     *
     * @param Job        $job
     * @param \Exception $exception
     */
    public function __construct(Job $job, \Exception $exception)
    {
        parent::__construct($job, $exception);
    }
}
