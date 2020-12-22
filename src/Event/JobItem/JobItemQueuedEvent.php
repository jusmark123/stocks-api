<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\JobItem;

use App\Event\AbstractJobItemEvent;

class JobItemQueuedEvent extends AbstractJobItemEvent
{
    const EVENT_NAME = 'job-item.queued';
}