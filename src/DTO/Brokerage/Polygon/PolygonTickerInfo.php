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
     * @var bool
     */
    private $active;

    /**
     * @var string
     */
    private $bloomberg;

    /**
     * @var string
     */
    private $ceo;

    /**
     * @var string
     */
    private $cik;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $employees;

    /**
     * @var string
     */
    private $exchange;

    /**
     * @var string
     */
    private $exchangeSymbol;

    /**
     * @var string|null
     */
    private $figi;

    /**
     * @var string
     */
    private $hqAddress;

    /**
     * @var string
     */
    private $hqState;

    /**
     * @var string
     */
    private $hqCountry;

    /**
     * @var string
     */
    private $industry;

    /**
     * @var \DateTime
     */
    private $listDate;

    /**
     * @var string
     */
    private $logo;

    /**
     * @var string
     */
    private $market;

    /**
     * @var int
     */
    private $marketCap;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $sector;

    /**
     * @var string
     */
    private $sic;

    /**
     * @var array
     */
    private $similar;

    /**
     * @var string
     */
    private $symbol;

    /**
     * @var array
     */
    private $tags;

    /**
     * @var string|null
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
    public function getBloomberg(): string
    {
        return $this->bloomberg;
    }

    /**
     * @param string $bloomberg
     *
     * @return PolygonTickerInfo
     */
    public function setBloomberg(string $bloomberg): PolygonTickerInfo
    {
        $this->bloomberg = $bloomberg;

        return $this;
    }

    /**
     * @return string
     */
    public function getCeo(): string
    {
        return $this->ceo;
    }

    /**
     * @param string $ceo
     *
     * @return PolygonTickerInfo
     */
    public function setCeo(string $ceo): PolygonTickerInfo
    {
        $this->ceo = $ceo;

        return $this;
    }

    /**
     * @return string
     */
    public function getCik(): string
    {
        return $this->cik;
    }

    /**
     * @param string $cik
     *
     * @return PolygonTickerInfo
     */
    public function setCik(string $cik): PolygonTickerInfo
    {
        $this->cik = $cik;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return PolygonTickerInfo
     */
    public function setCountry(string $country): PolygonTickerInfo
    {
        $this->country = $country;

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
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return PolygonTickerInfo
     */
    public function setDescription(string $description): PolygonTickerInfo
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int
     */
    public function getEmployees(): int
    {
        return $this->employees;
    }

    /**
     * @param int $employees
     *
     * @return PolygonTickerInfo
     */
    public function setEmployees(int $employees): PolygonTickerInfo
    {
        $this->employees = $employees;

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
     * @return PolygonTickerInfo
     */
    public function setExchange(string $exchange): PolygonTickerInfo
    {
        $this->exchange = $exchange;

        return $this;
    }

    /**
     * @return string
     */
    public function getExchangeSymbol(): string
    {
        return $this->exchangeSymbol;
    }

    /**
     * @param string $exchangeSymbol
     *
     * @return PolygonTickerInfo
     */
    public function setExchangeSymbol(string $exchangeSymbol): PolygonTickerInfo
    {
        $this->exchangeSymbol = $exchangeSymbol;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFigi(): ?string
    {
        return $this->figi;
    }

    /**
     * @param string|null $figi
     *
     * @return PolygonTickerInfo
     */
    public function setFigi(?string $figi): PolygonTickerInfo
    {
        $this->figi = $figi;

        return $this;
    }

    /**
     * @return string
     */
    public function getHqAddress(): string
    {
        return $this->hqAddress;
    }

    /**
     * @param string $hqAddress
     *
     * @return PolygonTickerInfo
     */
    public function setHqAddress(string $hqAddress): PolygonTickerInfo
    {
        $this->hqAddress = $hqAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getHqState(): string
    {
        return $this->hqState;
    }

    /**
     * @param string $hqState
     *
     * @return PolygonTickerInfo
     */
    public function setHqState(string $hqState): PolygonTickerInfo
    {
        $this->hqState = $hqState;

        return $this;
    }

    /**
     * @return string
     */
    public function getHqCountry(): string
    {
        return $this->hqCountry;
    }

    /**
     * @param string $hqCountry
     *
     * @return PolygonTickerInfo
     */
    public function setHqCountry(string $hqCountry): PolygonTickerInfo
    {
        $this->hqCountry = $hqCountry;

        return $this;
    }

    /**
     * @return string
     */
    public function getIndustry(): string
    {
        return $this->industry;
    }

    /**
     * @param string $industry
     *
     * @return PolygonTickerInfo
     */
    public function setIndustry(string $industry): PolygonTickerInfo
    {
        $this->industry = $industry;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getListDate(): \DateTime
    {
        return $this->listDate;
    }

    /**
     * @param \DateTime $listDate
     *
     * @return PolygonTickerInfo
     */
    public function setListDate(\DateTime $listDate): PolygonTickerInfo
    {
        $this->listDate = $listDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getLogo(): string
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     *
     * @return PolygonTickerInfo
     */
    public function setLogo(string $logo): PolygonTickerInfo
    {
        $this->logo = $logo;

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
     * @return int
     */
    public function getMarketCap(): int
    {
        return $this->marketCap;
    }

    /**
     * @param int $marketCap
     *
     * @return PolygonTickerInfo
     */
    public function setMarketCap(int $marketCap): PolygonTickerInfo
    {
        $this->marketCap = $marketCap;

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
    public function getSector(): string
    {
        return $this->sector;
    }

    /**
     * @param string $sector
     *
     * @return PolygonTickerInfo
     */
    public function setSector(string $sector): PolygonTickerInfo
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * @return string
     */
    public function getSic(): string
    {
        return $this->sic;
    }

    /**
     * @param string $sic
     *
     * @return PolygonTickerInfo
     */
    public function setSic(string $sic): PolygonTickerInfo
    {
        $this->sic = $sic;

        return $this;
    }

    /**
     * @return array
     */
    public function getSimilar(): array
    {
        return $this->similar;
    }

    /**
     * @param array $similar
     *
     * @return PolygonTickerInfo
     */
    public function setSimilar(array $similar): PolygonTickerInfo
    {
        $this->similar = $similar;

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
     * @return PolygonTickerInfo
     */
    public function setSymbol(string $symbol): PolygonTickerInfo
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     *
     * @return PolygonTickerInfo
     */
    public function setTags(array $tags): PolygonTickerInfo
    {
        $this->tags = $tags;

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
    public function getUpdated(): string
    {
        return $this->updated;
    }

    /**
     * @param string $updated
     *
     * @return PolygonTickerInfo
     */
    public function setUpdated(string $updated): PolygonTickerInfo
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
     * @return PolygonTickerInfo
     */
    public function setUrl(string $url): PolygonTickerInfo
    {
        $this->url = $url;

        return $this;
    }
}
