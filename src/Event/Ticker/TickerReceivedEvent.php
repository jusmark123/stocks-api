<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Ticker;

use App\Event\AbstractEvent;

/**
 * Class TickerReceivedEvent.
 */
class TickerReceivedEvent extends AbstractEvent
{
    /**
     * @var array
     */
    private $tickerMessage;

    /**
     * TickerReceivedEvent constructor.
     *
     * @param array $tickerMessage
     */
    public function __construct(array $tickerMessage)
    {
        $this->tickerMessage = $tickerMessage;
    }

    /**
     * @return array
     */
    public function getTickerMessage(): array
    {
        return $this->tickerMessage;
    }
}
