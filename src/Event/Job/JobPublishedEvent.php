<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Event\AbstractJobEvent;

/**
 * Class JobPublishedEvent.
 */
class JobPublishedEvent extends AbstractJobEvent
{
    const EVENT_NAME = 'job.published';
}
