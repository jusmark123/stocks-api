<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Ticker;

use App\Event\AbstractJobEvent;

/**
 * Class TickerProcessedEvent.
 */
class TickerProcessedEvent extends AbstractJobEvent
{
    use TickerEventTrait;

    const EVENT_NAME = 'ticker.processed';
}
