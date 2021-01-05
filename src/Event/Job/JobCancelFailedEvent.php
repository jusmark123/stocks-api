<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

/**
 * Class JobCancelFailedEvent.
 */
class JobCancelFailedEvent extends AbstractJobFailedEvent
{
    const EVENT_NAME = 'job.cancel';
}
