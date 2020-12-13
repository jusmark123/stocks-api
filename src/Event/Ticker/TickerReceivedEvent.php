<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Ticker;

use App\Entity\Job;
use App\Event\AbstractJobEvent;

/**
 * Class TickerReceivedEvent.
 */
class TickerReceivedEvent extends AbstractJobEvent
{
    const EVENT_NAME = 'ticker.receive';

    /**
     * @var array
     */
    private $tickerMessage;

    /**
     * TickerReceivedEvent constructor.
     *
     * @param array $tickerMessage
     */
    public function __construct(array $tickerMessage, Job $job)
    {
        $this->tickerMessage = $tickerMessage;
        parent::__construct($job);
    }

    /**
     * @return array
     */
    public function getTickerMessage(): array
    {
        return $this->tickerMessage;
    }
}
