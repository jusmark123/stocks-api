<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Event\AbstractJobFailedEvent;

/**
 * Class JobProcessFailedEvent.
 */
class JobProcessFailedEvent extends AbstractJobFailedEvent
{
    use JobFailedEventTrait;

    const EVENT_NAME = 'job.process';
}
