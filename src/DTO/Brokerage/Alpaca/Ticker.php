<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca;

use App\Constants\Brokerage\AlpacaConstants;
use App\DTO\Brokerage\BrokerageTickerInterface;

class Ticker implements BrokerageTickerInterface
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
     * @return Ticker
     */
    public function setClass(string $class): Ticker
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
     * @return Ticker
     */
    public function setSymbol(string $symbol): Ticker
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
     * @return Ticker
     */
    public function setStatus(bool $status): Ticker
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
     * @return Ticker
     */
    public function setTradable(bool $tradable): Ticker
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
     * @return Ticker
     */
    public function setMarginable(bool $marginable): Ticker
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
     * @return Ticker
     */
    public function setShortable(bool $shortable): Ticker
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
     * @return Ticker
     */
    public function setEasyToBorrow(bool $easyToBorrow): Ticker
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
     * @return Ticker
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
