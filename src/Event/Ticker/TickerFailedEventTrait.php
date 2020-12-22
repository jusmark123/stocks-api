<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Ticker;

use App\DTO\Brokerage\TickerInterface;
use App\Entity\JobItem;
use App\Entity\Ticker;

trait TickerFailedEventTrait
{
    use TickerEventTrait;

    /**
     * TickerProcessFailedEvent constructor.
     *
     * @param \Exception           $exception
     * @param JobItem              $jobItem
     * @param Ticker|null          $ticker
     * @param TickerInterface|null $tickerInfo
     */
    public function __construct(
        \Exception $exception,
        JobItem $jobItem,
        ?Ticker $ticker = null,
        ?TickerInterface $tickerInfo = null
    ) {
        $this->tickerMessage = $jobItem->getData();
        $this->ticker = $ticker;
        $this->tickerInfo = $tickerInfo;
        parent::__construct($exception, $jobItem->getJob(), $jobItem);
    }
}
