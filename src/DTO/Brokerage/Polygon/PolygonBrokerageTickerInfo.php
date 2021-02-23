<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Polygon;

use App\DTO\Brokerage\BrokerageTickerInterface;

/**
 * Class PolygonBrokerageTickerInfo.
 */
class PolygonBrokerageTickerInfo implements BrokerageTickerInterface
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
     * @return PolygonBrokerageTickerInfo
     */
    public function setTicker(string $ticker): PolygonBrokerageTickerInfo
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
     * @return PolygonBrokerageTickerInfo
     */
    public function setName(string $name): PolygonBrokerageTickerInfo
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
     * @return PolygonBrokerageTickerInfo
     */
    public function setMarket(string $market): PolygonBrokerageTickerInfo
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
     * @return PolygonBrokerageTickerInfo
     */
    public function setLocale(string $locale): PolygonBrokerageTickerInfo
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
     * @return PolygonBrokerageTickerInfo
     */
    public function setType(?string $type): PolygonBrokerageTickerInfo
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
     * @return PolygonBrokerageTickerInfo
     */
    public function setCurrency(string $currency): PolygonBrokerageTickerInfo
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
     * @return PolygonBrokerageTickerInfo
     */
    public function setActive(bool $active): PolygonBrokerageTickerInfo
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
     * @return PolygonBrokerageTickerInfo
     */
    public function setPrimaryExch(string $primaryExch): PolygonBrokerageTickerInfo
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
