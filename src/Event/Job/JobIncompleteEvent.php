<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Event\AbstractJobEvent;

/**
 * Class JobIncompleteEvent.
 */
class JobIncompleteEvent extends AbstractJobEvent
{
    const EVENT_NAME = 'job.incomplete';
}
