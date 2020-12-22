<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Ticker;

use App\DTO\Brokerage\TickerInterface;
use App\Entity\JobItem;
use App\Entity\Ticker;

trait TickerEventTrait
{
    /**
     * @var Ticker|null
     */
    protected $ticker;

    /**
     * @var array|null
     */
    protected $tickerMessage;

    /**
     * @var TickerInterface|null
     */
    protected $tickerInfo;

    /**
     * TickerProcessedEvent constructor.
     *
     * @param JobItem              $jobItem
     * @param Ticker|null          $ticker
     * @param TickerInterface|null $tickerInfo
     */
    public function __construct(
        JobItem $jobItem,
        ?Ticker $ticker = null,
        ?TickerInterface $tickerInfo = null
    ) {
        $this->ticker = $ticker;
        $this->tickerInfo = $tickerInfo;
        $this->tickerMessage = $jobItem->getData();
        parent::__construct($jobItem->getJob(), $jobItem);
    }

    /**
     * @return Ticker
     */
    public function getTicker(): Ticker
    {
        return $this->ticker;
    }

    /**
     * @return TickerInterface|null
     */
    public function getTickerInfo(): ?TickerInterface
    {
        return $this->tickerInfo;
    }

    /**
     * @return array|null
     */
    public function getTickerMessage(): ?array
    {
        return $this->tickerMessage;
    }
}
