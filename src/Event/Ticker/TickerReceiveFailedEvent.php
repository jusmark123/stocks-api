<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Ticker;

use App\Event\AbstractJobFailedEvent;

/**
 * Class TickerReceiveFailedEvent.
 */
class TickerReceiveFailedEvent extends AbstractJobFailedEvent
{
    use TickerFailedEventTrait;

    const EVENT_NAME = 'ticker.receive';
}
