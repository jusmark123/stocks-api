<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\JobItem;

use App\Event\Job\JobEventTrait;

/*
 * Class JobItemCancelledEvent.
 */
class JobItemCancelledEvent extends AbstractJobItemEvent
{
    use JobEventTrait;

    const EVENT_NAME = 'job-item.cancelled';
}
