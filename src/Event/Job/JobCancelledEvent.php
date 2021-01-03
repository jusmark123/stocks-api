<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

/**
 * Class JobCancelledEvent.
 */
class JobCancelledEvent extends AbstractJobEvent
{
    const EVENT_NAME = 'job.cancelled';
}
