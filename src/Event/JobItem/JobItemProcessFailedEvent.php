<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\JobItem;

use App\Event\AbstractJobItemFailedEvent;
use App\Event\Job\JobFailedEventTrait;

/**
 * Class JobItemProcessFailedEvent.
 */
class JobItemProcessFailedEvent extends AbstractJobItemFailedEvent
{
    use JobFailedEventTrait;

    const EVENT_NAME = 'job-item.processed';
}
