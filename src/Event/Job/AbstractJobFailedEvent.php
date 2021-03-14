<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Event\AbstractFailedEvent;

/**
 * Class AbstractJobFailedEvent.
 */
class AbstractJobFailedEvent extends AbstractFailedEvent
{
    use JobFailedEventTrait;
}
