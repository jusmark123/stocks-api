<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\JobItem;

/*
 * Class JobItemReceivedEvent.
 */

class JobItemReceivedEvent extends AbstractJobItemEvent
{
    const EVENT_NAME = 'job-item.received';
}
