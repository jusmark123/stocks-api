<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

/**
 * Class JobProcessedEvent.
 */
class JobProcessedEvent extends AbstractJobEvent
{
    const EVENT_NAME = 'job.processed';
}
