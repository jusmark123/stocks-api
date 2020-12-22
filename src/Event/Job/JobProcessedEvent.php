<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Event\AbstractJobEvent;

/**
 * Class JobProcessedEvent.
 */
class JobProcessedEvent extends AbstractJobEvent
{
    const EVENT_NAME = 'job.processed';
}
