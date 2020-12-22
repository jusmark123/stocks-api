<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Ticker;

use App\Event\AbstractJobEvent;

/**
 * Class TickerReceivedEvent.
 */
class TickerReceivedEvent extends AbstractJobEvent
{
    use TickerEventTrait;

    const EVENT_NAME = 'ticker.receive';
}
