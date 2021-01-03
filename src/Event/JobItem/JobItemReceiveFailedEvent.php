<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\JobItem;

/*
 * Class JobItemReceiveFailedEvent.
 */

class JobItemReceiveFailedEvent extends AbstractJobItemFailedEvent
{
    const EVENT_NAME = 'job-item.receive';
}
