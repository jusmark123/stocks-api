<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\JobItem;

use App\Event\AbstractJobItemEvent;

class JobItemProcessingEvent extends AbstractJobItemEvent
{
    const EVENT_NAME = 'job-item.processing';
}
