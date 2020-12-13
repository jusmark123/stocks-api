<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Ticker;

use App\Entity\Job;
use App\Entity\Ticker;
use App\Event\AbstractJobFailedEvent;

/**
 * Class TickerReceiveFailedEvent.
 */
class TickerReceiveFailedEvent extends AbstractJobFailedEvent
{
    const EVENT_NAME = 'ticker.receive';

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
    public function __construct(array $tickerMessage, \Exception $exception, Job $job, ?Ticker $ticker = null)
    {
        $this->tickerMessage = $tickerMessage;
        $this->ticker = $ticker;
        parent::__construct($job, $exception);
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
