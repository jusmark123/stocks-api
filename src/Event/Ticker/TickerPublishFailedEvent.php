<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Ticker;

use App\Event\AbstractJobFailedEvent;

/**
 * Class TickerPublishFailedEvent.
 */
class TickerPublishFailedEvent extends AbstractJobFailedEvent
{
    use TickerFailedEventTrait;

    const EVENT_NAME = 'ticker.publish';
}
