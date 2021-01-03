<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Polygon;

use App\DTO\Brokerage\TickerInterface;

/**
 * Class PolygonTickerInfo.
 */
class PolygonTickerInfo implements TickerInterface
{
    /**
     * @var string
     */
    private $ticker;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $market;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var bool
     */
    private $active;

    /**
     * @var string|null
     */
    private $primaryExch;

    /**
     * @return string
     */
    public function getTicker(): string
    {
        return $this->ticker;
    }

    /**
     * @param string $ticker
     *
     * @return PolygonTickerInfo
     */
    public function setTicker(string $ticker): PolygonTickerInfo
    {
        $this->ticker = $ticker;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return PolygonTickerInfo
     */
    public function setName(string $name): PolygonTickerInfo
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getMarket(): string
    {
        return $this->market;
    }

    /**
     * @param string $market
     *
     * @return PolygonTickerInfo
     */
    public function setMarket(string $market): PolygonTickerInfo
    {
        $this->market = $market;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     *
     * @return PolygonTickerInfo
     */
    public function setLocale(string $locale): PolygonTickerInfo
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     *
     * @return PolygonTickerInfo
     */
    public function setType(?string $type): PolygonTickerInfo
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return PolygonTickerInfo
     */
    public function setCurrency(string $currency): PolygonTickerInfo
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return PolygonTickerInfo
     */
    public function setActive(bool $active): PolygonTickerInfo
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrimaryExch(): string
    {
        return $this->primaryExch;
    }

    /**
     * @param string $primaryExch
     *
     * @return PolygonTickerInfo
     */
    public function setPrimaryExch(string $primaryExch): PolygonTickerInfo
    {
        $this->primaryExch = $primaryExch;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getExchange(): ?string
    {
        return $this->primaryExch;
    }
}
