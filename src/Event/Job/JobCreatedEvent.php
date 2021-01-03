<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

/**
 * Class JobCreatedEvent.
 */
class JobCreatedEvent extends AbstractJobEvent
{
    const EVENT_NAME = 'job.created';
}
