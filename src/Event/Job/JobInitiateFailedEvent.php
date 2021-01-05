<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

/**
 * Class JobInitiateFailedEvent.
 */
class JobInitiateFailedEvent extends AbstractJobFailedEvent
{
    use JobFailedEventTrait;

    const EVENT_NAME = 'job.initiate';
}
