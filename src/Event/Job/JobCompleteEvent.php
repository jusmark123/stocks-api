<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

/**
 * Class JobCompleteEvent.
 */
class JobCompleteEvent extends AbstractJobEvent
{
    const EVENT_NAME = 'job.complete';
}
