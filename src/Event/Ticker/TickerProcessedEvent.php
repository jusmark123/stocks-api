<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Ticker;

use App\Entity\Job;
use App\Entity\Ticker;
use App\Event\AbstractJobEvent;

/**
 * Class TickerProcessedEvent.
 */
class TickerProcessedEvent extends AbstractJobEvent
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
     * @param Job    $job
     */
    public function __construct(Ticker $ticker, Job $job)
    {
        $this->ticker = $ticker;
        parent::__construct($job);
    }

    /**
     * @return Ticker
     */
    public function getTicker(): Ticker
    {
        return $this->ticker;
    }
}
