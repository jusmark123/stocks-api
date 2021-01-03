<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

/**
 * Class JobProcessingEvent.
 */
class JobProcessingEvent extends AbstractJobEvent
{
    const EVENT_NAME = 'job.processing';
}
