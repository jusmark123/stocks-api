<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Polygon;

use App\DTO\Brokerage\TickerInterface;

/**
 * Class PolygonTicker.
 */
class PolygonTicker implements TickerInterface
{
    /**
     * @var bool
     */
    private $active;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var string
     */
    private $market;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $primaryExch;

    /**
     * @var string
     */
    private $ticker;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $updated;

    /**
     * @var string
     */
    private $url;

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
     * @return PolygonTicker
     */
    public function setActive(bool $active): PolygonTicker
    {
        $this->active = $active;

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
     * @return PolygonTicker
     */
    public function setCurrency(string $currency): PolygonTicker
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string
     */
    public function getExchange(): string
    {
        return $this->primaryExch;
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
     * @return PolygonTicker
     */
    public function setLocale(string $locale): PolygonTicker
    {
        $this->locale = $locale;

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
     * @return PolygonTicker
     */
    public function setMarket(string $market): PolygonTicker
    {
        $this->market = $market;

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
     * @return PolygonTicker
     */
    public function setName(string $name): PolygonTicker
    {
        $this->name = $name;

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
     * @return PolygonTicker
     */
    public function setPrimaryExch(string $primaryExch): PolygonTicker
    {
        $this->primaryExch = $primaryExch;

        return $this;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->ticker;
    }

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
     * @return PolygonTicker
     */
    public function setTicker(string $ticker): PolygonTicker
    {
        $this->ticker = $ticker;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return PolygonTicker
     */
    public function setType(string $type): PolygonTicker
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getUpdated(): string
    {
        return $this->updated;
    }

    /**
     * @param string $updated
     *
     * @return PolygonTicker
     */
    public function setUpdated(string $updated): PolygonTicker
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return PolygonTicker
     */
    public function setUrl(string $url): PolygonTicker
    {
        $this->url = $url;

        return $this;
    }
}
