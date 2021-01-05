<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

/**
 * Class JobPublishFailedEvent.
 */
class JobPublishFailedEvent extends AbstractJobFailedEvent
{
    const EVENT_NAME = 'job.publish';
}
