<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca;

use App\DTO\Brokerage\BrokeragePositionInterface;

/**
 * Class AlpacaPosition.
 */
class AlpacaPosition implements BrokeragePositionInterface
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
     * @return AlpacaPosition
     */
    public function setAssetId(string $assetId): AlpacaPosition
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
     * @return AlpacaPosition
     */
    public function setSymbol(string $symbol): AlpacaPosition
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
     * @return AlpacaPosition
     */
    public function setAssetClass(string $assetClass): AlpacaPosition
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
     * @return AlpacaPosition
     */
    public function setAvgEntryPrice(string $avgEntryPrice): AlpacaPosition
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
     * @return AlpacaPosition
     */
    public function setChangeToday(string $changeToday): AlpacaPosition
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
     * @return AlpacaPosition
     */
    public function setCostBasis(string $costBasis): AlpacaPosition
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
     * @return AlpacaPosition
     */
    public function setCurrentPrice(string $currentPrice): AlpacaPosition
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
     * @return AlpacaPosition
     */
    public function setExchange(string $exchange): AlpacaPosition
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
     * @return AlpacaPosition
     */
    public function setLastDayPrice(string $lastDayPrice): AlpacaPosition
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
     * @return AlpacaPosition
     */
    public function setMarketValue(string $marketValue): AlpacaPosition
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
     * @return AlpacaPosition
     */
    public function setQty(string $qty): AlpacaPosition
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
     * @return AlpacaPosition
     */
    public function setSide(string $side): AlpacaPosition
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
     * @return AlpacaPosition
     */
    public function setUnrealizedPl(string $unrealizedPl): AlpacaPosition
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
     * @return AlpacaPosition
     */
    public function setUnrealizedPlpc(string $unrealizedPlpc): AlpacaPosition
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
     * @return AlpacaPosition
     */
    public function setUnrealizedIntradayPl(string $unrealizedIntradayPl): AlpacaPosition
    {
        $this->unrealizedIntradayPl = (float) $unrealizedIntradayPl;

        return $this;
    }
}
