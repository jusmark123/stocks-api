<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\YahooFinance\Entity;

/**
 * Class YahooFinanceTicker.
 */
class YahooFinanceTicker
{
    /**
     * @var string
     */
    private string $currency = 'USD';

    /**
     * @var string
     */
    private string $exchange = '';

    /**
     * @var string|null
     */
    private ?string $industryName = null;

    /**
     * @var string|null
     */
    private ?string $industryLink = null;

    /**
     * @var string
     */
    private string $quoteType = '';

    /**
     * @var int
     */
    private int $rank = 0;

    /**
     * @var float
     */
    private float $regularMarketPrice = 0.00;

    /**
     * @var float
     */
    private float $regularMarketChange = 0.00;

    /**
     * @var float
     */
    private float $regularMarketPercentChange = 0.00;

    /**
     * @var string
     */
    private string $shortName = '';

    /**
     * @var string
     */
    private string $symbol;

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string|null $currency
     *
     * @return YahooFinanceTicker
     */
    public function setCurrency(?string $currency): YahooFinanceTicker
    {
        $this->currency = $currency ?? 'USD';

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
     * @return YahooFinanceTicker
     */
    public function setExchange(string $exchange): YahooFinanceTicker
    {
        $this->exchange = $exchange;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIndustryName(): ?string
    {
        return $this->industryName;
    }

    /**
     * @param string|null $industryName
     *
     * @return YahooFinanceTicker
     */
    public function setIndustryName(?string $industryName): YahooFinanceTicker
    {
        $this->industryName = $industryName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIndustryLink(): ?string
    {
        return $this->industryLink;
    }

    /**
     * @param string|null $industryLink
     *
     * @return YahooFinanceTicker
     */
    public function setIndustryLink(?string $industryLink): YahooFinanceTicker
    {
        $this->industryLink = $industryLink;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuoteType(): string
    {
        return $this->quoteType;
    }

    /**
     * @param string $quoteType
     *
     * @return YahooFinanceTicker
     */
    public function setQuoteType(string $quoteType): YahooFinanceTicker
    {
        $this->quoteType = $quoteType;

        return $this;
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * @param int $rank
     *
     * @return YahooFinanceTicker
     */
    public function setRank(int $rank): YahooFinanceTicker
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * @return float
     */
    public function getRegularMarketPrice(): float
    {
        return $this->regularMarketPrice;
    }

    /**
     * @param float $regularMarketPrice
     *
     * @return YahooFinanceTicker
     */
    public function setRegularMarketPrice(float $regularMarketPrice): YahooFinanceTicker
    {
        $this->regularMarketPrice = $regularMarketPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getRegularMarketChange(): float
    {
        return $this->regularMarketChange;
    }

    /**
     * @param float $regularMarketChange
     *
     * @return YahooFinanceTicker
     */
    public function setRegularMarketChange(float $regularMarketChange): YahooFinanceTicker
    {
        $this->regularMarketChange = $regularMarketChange;

        return $this;
    }

    /**
     * @return float
     */
    public function getRegularMarketPercentChange(): float
    {
        return $this->regularMarketPercentChange;
    }

    /**
     * @param float $regularMarketPercentChange
     *
     * @return YahooFinanceTicker
     */
    public function setRegularMarketPercentChange(float $regularMarketPercentChange): YahooFinanceTicker
    {
        $this->regularMarketPercentChange = $regularMarketPercentChange;

        return $this;
    }

    /**
     * @return string
     */
    public function getShortName(): string
    {
        return $this->shortName;
    }

    /**
     * @param string $shortName
     *
     * @return YahooFinanceTicker
     */
    public function setShortName(string $shortName): YahooFinanceTicker
    {
        $this->shortName = $shortName;

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
     * @return YahooFinanceTicker
     */
    public function setSymbol(string $symbol): YahooFinanceTicker
    {
        $this->symbol = $symbol;

        return $this;
    }
}
