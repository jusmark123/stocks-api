<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\JobItem;

/*
 * Class JobItemQueueFailedEvent.
 */

class JobItemQueueFailedEvent extends AbstractJobItemFailedEvent
{
    const EVENT_NAME = 'job-item.queue';
}
