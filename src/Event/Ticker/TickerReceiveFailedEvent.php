<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Ticker;

use App\Entity\Ticker;
use App\Event\AbstractFailedEvent;

/**
 * Class TickerReceiveFailedEvent.
 */
class TickerReceiveFailedEvent extends AbstractFailedEvent
{
    /**
     * @var array
     */
    private $tickerMessage;

    /**
     * @var Ticker|null
     */
    private $ticker;

    /**
     * TickerReceiveFailedEvent constructor.
     *
     * @param array       $tickerMessage
     * @param \Exception  $exception
     * @param Ticker|null $ticker
     */
    public function __construct(array $tickerMessage, \Exception $exception, ?Ticker $ticker = null)
    {
        $this->tickerMessage = $tickerMessage;
        $this->ticker = $ticker;
        parent::__construct($exception);
    }

    /**
     * @return array
     */
    public function getTickerMessage(): array
    {
        return $this->tickerMessage;
    }

    /**
     * @return Ticker|null
     */
    public function getTicker(): ?Ticker
    {
        return $this->ticker;
    }
}
