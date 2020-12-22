<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Event\AbstractJobEvent;

/**
 * Class JobInProgressEvent.
 */
class JobInProgressEvent extends AbstractJobEvent
{
    const EVENT_NAME = 'job.in-progress';
}
