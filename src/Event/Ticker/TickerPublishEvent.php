<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Ticker;

use App\Entity\Job;
use App\Entity\Ticker;
use App\Event\AbstractJobEvent;

class TickerPublishEvent extends AbstractJobEvent
{
    const EVENT_NAME = 'ticker.process';

    /**
     * @var array
     */
    private $tickerMessage;

    /**
     * @var Ticker|null
     */
    private $ticker;

    /**
     * TickerPublishEvent constructor.
     *
     * @param array       $tickerMessage
     * @param Job         $job
     * @param Ticker|null $ticker
     */
    public function __construct(array $tickerMessage, Job $job, ?Ticker $ticker)
    {
        $this->tickerMessage = $tickerMessage;
        $this->ticker = $ticker;
        parent::__construct($job);
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
