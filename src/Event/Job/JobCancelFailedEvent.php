<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Event\AbstractJobFailedEvent;

class JobCancelFailedEvent extends AbstractJobFailedEvent
{
    const EVENT_NAME = 'job.cancel';
}
