<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Ticker;

use App\Event\AbstractFailedEvent;

/**
 * Class TickerPublishFailedEvent.
 */
class TickerPublishFailedEvent extends AbstractFailedEvent
{
    /**
     * @var array
     */
    private $tickerMessage;

    /**
     * TickerPublishFailedEvent constructor.
     *
     * @param array      $tickerMessage
     * @param \Exception $exception
     */
    public function __construct(array $tickerMessage, \Exception $exception)
    {
        $this->tickerMessage = $tickerMessage;
        parent::__construct($exception);
    }

    /**
     * @return array
     */
    public function getTickerMessage()
    {
        return $this->tickerMessage;
    }
}
