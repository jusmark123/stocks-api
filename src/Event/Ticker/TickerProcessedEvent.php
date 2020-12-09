<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Ticker;

use App\Entity\Ticker;
use App\Event\AbstractEvent;

class TickerProcessedEvent extends AbstractEvent
{
    const EVENT_NAME = 'ticker.processed';

    /**
     * @var Ticker
     */
    protected $ticker;

    /**
     * TickerProcessedEvent constructor.
     *
     * @param Ticker $ticker
     */
    public function __construct(Ticker $ticker)
    {
        $this->ticker = $ticker;
    }

    /**
     * @return Ticker
     */
    public function getTicker(): Ticker
    {
        return $this->ticker;
    }
}
