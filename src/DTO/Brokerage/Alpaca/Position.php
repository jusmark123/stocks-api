<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca;

use App\DTO\Brokerage\BrokeragePositionInterface;

/**
 * Class Position.
 */
class Position implements BrokeragePositionInterface
{
    /**
     * @var string
     */
    private $assetId;

    /**
     * @var string
     */
    private $symbol;

    /**
     * @var string
     */
    private $assetClass;

    /**
     * @var float
     */
    private $avgEntryPrice;

    /**
     * @var float
     */
    private $changeToday;

    /**
     * @var float
     */
    private $costBasis;

    /**
     * @var float
     */
    private $currentPrice;

    /**
     * @var string
     */
    private $exchange;

    /**
     * @var float
     */
    private $lastDayPrice;

    /**
     * @var float
     */
    private $marketValue;

    /**
     * @var int
     */
    private $qty;

    /**
     * @var string
     */
    private $side;

    /**
     * @var float
     */
    private $unrealizedPl;

    /**
     * @var float
     */
    private $unrealizedPlpc;

    /**
     * @var float
     */
    private $unrealizedIntradayPl;

    /**
     * @return string
     */
    public function getAssetId(): string
    {
        return $this->assetId;
    }

    /**
     * @param string $assetId
     *
     * @return Position
     */
    public function setAssetId(string $assetId): Position
    {
        $this->assetId = $assetId;

        return $this;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     *
     * @return Position
     */
    public function setSymbol(string $symbol): Position
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * @return string
     */
    public function getAssetClass(): string
    {
        return $this->assetClass;
    }

    /**
     * @param string $assetClass
     *
     * @return Position
     */
    public function setAssetClass(string $assetClass): Position
    {
        $this->assetClass = $assetClass;

        return $this;
    }

    /**
     * @return float
     */
    public function getAvgEntryPrice(): float
    {
        return $this->avgEntryPrice;
    }

    /**
     * @param float $avgEntryPrice
     *
     * @return Position
     */
    public function setAvgEntryPrice(string $avgEntryPrice): Position
    {
        $this->avgEntryPrice = (float) $avgEntryPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getChangeToday(): float
    {
        return $this->changeToday;
    }

    /**
     * @param float $changeToday
     *
     * @return Position
     */
    public function setChangeToday(string $changeToday): Position
    {
        $this->changeToday = (float) $changeToday;

        return $this;
    }

    /**
     * @return float
     */
    public function getCostBasis(): float
    {
        return $this->costBasis;
    }

    /**
     * @param float $costBasis
     *
     * @return Position
     */
    public function setCostBasis(string $costBasis): Position
    {
        $this->costBasis = (float) $costBasis;

        return $this;
    }

    /**
     * @return float
     */
    public function getCurrentPrice(): float
    {
        return $this->currentPrice;
    }

    /**
     * @param float $currentPrice
     *
     * @return Position
     */
    public function setCurrentPrice(string $currentPrice): Position
    {
        $this->currentPrice = (float) $currentPrice;

        return $this;
    }

    /**
     * @return string
     */
    public function getExchange(): string
    {
        return $this->exchange;
    }

    /**
     * @param string $exchange
     *
     * @return Position
     */
    public function setExchange(string $exchange): Position
    {
        $this->exchange = $exchange;

        return $this;
    }

    /**
     * @return float
     */
    public function getLastDayPrice(): float
    {
        return $this->lastDayPrice;
    }

    /**
     * @param float $lastDayPrice
     *
     * @return Position
     */
    public function setLastDayPrice(string $lastDayPrice): Position
    {
        $this->lastDayPrice = (float) $lastDayPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getMarketValue(): float
    {
        return $this->marketValue;
    }

    /**
     * @param float $marketValue
     *
     * @return Position
     */
    public function setMarketValue(string $marketValue): Position
    {
        $this->marketValue = (float) $marketValue;

        return $this;
    }

    /**
     * @return int
     */
    public function getQty(): int
    {
        return $this->qty;
    }

    /**
     * @param int $qty
     *
     * @return Position
     */
    public function setQty(string $qty): Position
    {
        $this->qty = (int) $qty;

        return $this;
    }

    /**
     * @return string
     */
    public function getSide(): string
    {
        return $this->side;
    }

    /**
     * @param string $side
     *
     * @return Position
     */
    public function setSide(string $side): Position
    {
        $this->side = $side;

        return $this;
    }

    /**
     * @return float
     */
    public function getUnrealizedPl(): float
    {
        return $this->unrealizedPl;
    }

    /**
     * @param float $unrealizedPl
     *
     * @return Position
     */
    public function setUnrealizedPl(string $unrealizedPl): Position
    {
        $this->unrealizedPl = (float) $unrealizedPl;

        return $this;
    }

    /**
     * @return float
     */
    public function getUnrealizedPlpc(): float
    {
        return $this->unrealizedPlpc;
    }

    /**
     * @param float $unrealizedPlpc
     *
     * @return Position
     */
    public function setUnrealizedPlpc(string $unrealizedPlpc): Position
    {
        $this->unrealizedPlpc = (float) $unrealizedPlpc;

        return $this;
    }

    /**
     * @return float
     */
    public function getUnrealizedIntradayPl(): float
    {
        return $this->unrealizedIntradayPl;
    }

    /**
     * @param string $unrealizedIntradayPl
     *
     * @return Position
     */
    public function setUnrealizedIntradayPl(string $unrealizedIntradayPl): Position
    {
        $this->unrealizedIntradayPl = (float) $unrealizedIntradayPl;

        return $this;
    }
}
