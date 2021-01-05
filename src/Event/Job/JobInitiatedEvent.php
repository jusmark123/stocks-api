<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

/**
 * Class JobInitiatedEvent.
 */
class JobInitiatedEvent extends AbstractJobEvent
{
    const EVENT_NAME = 'job.initiated';
}
