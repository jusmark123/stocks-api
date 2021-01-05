<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Polygon;

/**
 * Class PolygonTickerDetail.
 */
class PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setActive(bool $active): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setBloomberg(string $bloomberg): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setCeo(string $ceo): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setCik(string $cik): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setCountry(string $country): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setCurrency(string $currency): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setDescription(string $description): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setEmployees(int $employees): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setExchange(string $exchange): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setExchangeSymbol(string $exchangeSymbol): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setFigi(?string $figi): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setHqAddress(string $hqAddress): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setHqState(string $hqState): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setHqCountry(string $hqCountry): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setIndustry(string $industry): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setListDate(\DateTime $listDate): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setLogo(string $logo): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setMarket(string $market): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setMarketCap(int $marketCap): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setName(string $name): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setSector(string $sector): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setSic(string $sic): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setSimilar(array $similar): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setSymbol(string $symbol): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setTags(array $tags): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setType(?string $type): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setUpdated(string $updated): PolygonTickerDetail
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
     * @return PolygonTickerDetail
     */
    public function setUrl(string $url): PolygonTickerDetail
    {
        $this->url = $url;

        return $this;
    }
}
