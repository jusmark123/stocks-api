<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

/**
 * Class JobCreateFailedEvent.
 */
class JobCreateFailedEvent extends AbstractJobFailedEvent
{
    use JobFailedEventTrait;

    const EVENT_NAME = 'job.create';
}
