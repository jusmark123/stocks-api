<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

/**
 * Class JobReceivedEvent.
 */
class JobReceivedEvent extends AbstractJobEvent
{
    const EVENT_NAME = 'job.received';
}
