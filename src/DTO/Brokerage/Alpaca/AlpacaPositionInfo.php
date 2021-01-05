<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca;

/**
 * Class AlpacaPositionInfo.
 */
class AlpacaPositionInfo
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
    private $unrealizeIntradayPl;

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
     * @return AlpacaPositionInfo
     */
    public function setAssetId(string $assetId): AlpacaPositionInfo
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
     * @return AlpacaPositionInfo
     */
    public function setSymbol(string $symbol): AlpacaPositionInfo
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
     * @return AlpacaPositionInfo
     */
    public function setAssetClass(string $assetClass): AlpacaPositionInfo
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
     * @return AlpacaPositionInfo
     */
    public function setAvgEntryPrice(string $avgEntryPrice): AlpacaPositionInfo
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
     * @return AlpacaPositionInfo
     */
    public function setChangeToday(string $changeToday): AlpacaPositionInfo
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
     * @return AlpacaPositionInfo
     */
    public function setCostBasis(string $costBasis): AlpacaPositionInfo
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
     * @return AlpacaPositionInfo
     */
    public function setCurrentPrice(string $currentPrice): AlpacaPositionInfo
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
     * @return AlpacaPositionInfo
     */
    public function setExchange(string $exchange): AlpacaPositionInfo
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
     * @return AlpacaPositionInfo
     */
    public function setLastDayPrice(string $lastDayPrice): AlpacaPositionInfo
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
     * @return AlpacaPositionInfo
     */
    public function setMarketValue(string $marketValue): AlpacaPositionInfo
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
     * @return AlpacaPositionInfo
     */
    public function setQty(string $qty): AlpacaPositionInfo
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
     * @return AlpacaPositionInfo
     */
    public function setSide(string $side): AlpacaPositionInfo
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
     * @return AlpacaPositionInfo
     */
    public function setUnrealizedPl(string $unrealizedPl): AlpacaPositionInfo
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
     * @return AlpacaPositionInfo
     */
    public function setUnrealizedPlpc(string $unrealizedPlpc): AlpacaPositionInfo
    {
        $this->unrealizedPlpc = (float) $unrealizedPlpc;

        return $this;
    }

    /**
     * @return float
     */
    public function getUnrealizeIntradayPl(): float
    {
        return $this->unrealizeIntradayPl;
    }

    /**
     * @param float $unrealizeIntradayPl
     *
     * @return AlpacaPositionInfo
     */
    public function setUnrealizeIntradayPl(string $unrealizeIntradayPl): AlpacaPositionInfo
    {
        $this->unrealizeIntradayPl = (float) $unrealizeIntradayPl;

        return $this;
    }
}
