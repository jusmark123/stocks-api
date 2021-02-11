<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca;

use App\Constants\Brokerage\AlpacaConstants;
use App\DTO\Brokerage\TickerInterface;

class TickerInfo implements TickerInterface
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $brokerage = AlpacaConstants::BROKERAGE_NAME;

    /**
     * @var bool
     */
    private $easyToBorrow;

    /**
     * @var bool
     */
    private $marginable;

    /**
     * @var bool
     */
    private $shortable;

    /**
     * @var bool
     */
    private $status;

    /**
     * @var string
     */
    private $symbol;

    /**
     * @var bool
     */
    private $tradable;

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $class
     *
     * @return TickerInfo
     */
    public function setClass(string $class): TickerInfo
    {
        $this->class = $class;

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
     * @return TickerInfo
     */
    public function setSymbol(string $symbol): TickerInfo
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     *
     * @return TickerInfo
     */
    public function setStatus(bool $status): TickerInfo
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTradable(): bool
    {
        return $this->tradable;
    }

    /**
     * @param bool $tradable
     *
     * @return TickerInfo
     */
    public function setTradable(bool $tradable): TickerInfo
    {
        $this->tradable = $tradable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isMarginable(): bool
    {
        return $this->marginable;
    }

    /**
     * @param bool $marginable
     *
     * @return TickerInfo
     */
    public function setMarginable(bool $marginable): TickerInfo
    {
        $this->marginable = $marginable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShortable(): bool
    {
        return $this->shortable;
    }

    /**
     * @param bool $shortable
     *
     * @return TickerInfo
     */
    public function setShortable(bool $shortable): TickerInfo
    {
        $this->shortable = $shortable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEasyToBorrow(): bool
    {
        return $this->easyToBorrow;
    }

    /**
     * @param bool $easyToBorrow
     *
     * @return TickerInfo
     */
    public function setEasyToBorrow(bool $easyToBorrow): TickerInfo
    {
        $this->easyToBorrow = $easyToBorrow;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBrokerage()
    {
        return $this->brokerage;
    }

    /**
     * @param mixed $brokerage
     *
     * @return TickerInfo
     */
    public function setBrokerage($brokerage)
    {
        $this->brokerage = $brokerage;

        return $this;
    }

    public function getType(): ?string
    {
        // TODO: Implement getType() method.
    }

    public function getExchange(): string
    {
        // TODO: Implement getExchange() method.
    }

    public function getCurrency(): string
    {
        // TODO: Implement getCurrency() method.
    }

    public function getName(): string
    {
        // TODO: Implement getName() method.
    }

    public function getTicker(): string
    {
        // TODO: Implement getTicker() method.
    }

    public function isActive(): bool
    {
        // TODO: Implement isActive() method.
    }

    public function getMarket(): string
    {
        // TODO: Implement getMarket() method.
    }
}
