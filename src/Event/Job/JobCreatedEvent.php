<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Event\AbstractJobEvent;

/**
 * Class JobCreatedEvent.
 */
class JobCreatedEvent extends AbstractJobEvent
{
    const EVENT_NAME = 'job.created';
}
