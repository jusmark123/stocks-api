<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\YahooFinance;

use App\DTO\Brokerage\TickerRequestInterface;

/**
 * Class TickerRequest.
 */
class TickerRequest implements TickerRequestInterface
{
    const ASSET_TYPES = ['equity', 'etf', 'index'];
    const BASE_URL = 'https://query1.finance.yahoo.com/v1/finance/lookup';
    const REQUEST_HEADERS = ['Content-Type' => 'application/json'];

    /**
     * @var bool
     */
    private bool $formatted = true;

    /**
     * @var string
     */
    private string $lang = 'en-US';

    /**
     * @var string
     */
    private string $region = 'US';

    /**
     * @var string|null
     */
    private ?string $query = null;

    /**
     * @var string
     */
    private string $type = 'equity';

    /**
     * @var int
     */
    private int $count = 3000;

    /**
     * @var int
     */
    private int $start = 0;

    /**
     * @var int|null
     */
    private ?int $limit = null;

    /**
     * @var array
     */
    private array $range;

    public function __construct()
    {
        $this->range = range('a', 'z');
    }

    /**
     * @return bool
     */
    public function isFormatted(): bool
    {
        return $this->formatted;
    }

    /**
     * @param bool $formatted
     *
     * @return TickerRequest
     */
    public function setFormatted(bool $formatted): TickerRequest
    {
        $this->formatted = $formatted;

        return $this;
    }

    /**
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }

    /**
     * @param string $lang
     *
     * @return TickerRequest
     */
    public function setLang(string $lang): TickerRequest
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @param string $region
     *
     * @return TickerRequest
     */
    public function setRegion(string $region): TickerRequest
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * @param string|null $query
     *
     * @return TickerRequest
     */
    public function setQuery(?string $query): TickerRequest
    {
        $this->query = $query;

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
     * @return TickerRequest
     */
    public function setType(string $type): TickerRequest
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     *
     * @return TickerRequest
     */
    public function setCount(int $count): TickerRequest
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @return int
     */
    public function getStart(): int
    {
        return $this->start;
    }

    /**
     * @param int $start
     *
     * @return TickerRequest
     */
    public function setStart(int $start): TickerRequest
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return array
     */
    public function getRange(): array
    {
        return $this->range;
    }

    /**
     * @param array $range
     *
     * @return TickerRequest
     */
    public function setRange(array $range): TickerRequest
    {
        $this->range = $range;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     *
     * @return TickerRequest
     */
    public function setLimit(?int $limit): TickerRequest
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return [
            'formatted' => $this->formatted,
            'lang' => $this->lang,
            'region' => $this->region,
            'query' => $this->query,
            'type' => $this->type,
            'count' => $this->count,
            'start' => $this->start,
        ];
    }
}
