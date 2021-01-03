<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

/**
 * Class JobReceivedFailedEvent.
 */
class JobReceiveFailedEvent extends AbstractJobFailedEvent
{
    use JobFailedEventTrait;

    const EVENT_NAME = 'job.receive';
}
