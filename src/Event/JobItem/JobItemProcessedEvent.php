<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\JobItem;

/*
 * Class JobItemProcessedEvent.
 */

class JobItemProcessedEvent extends AbstractJobItemEvent
{
    const EVENT_NAME = 'job-item.event';
}
