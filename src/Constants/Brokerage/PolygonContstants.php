<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Constants\Brokerage;

final class PolygonContstants
{
    const BROKERAGE_NAME = AlpacaConstants::BROKERAGE_NAME;

    const API_KEY = 'PKU2P6NISHZELU5ATWEQ';
    const API_URL_BASE = 'https://api.polygon.io/v2';

    const URL_API_KEY_SUFFIX = '?apiKey='.self::API_KEY;
    const ALL_TICKERS_ENDPOINT = '/snapshot/locale/us/markets/stocks/tickers';
    const TICKER_ENDPOINT = '/reference/tickers';
    const TICKER_DETAIL_ENDPOINT = '/meta/symbols/%s/company';
    const TICKER_TYPE_ENDPOINT = '/reference/types';
    const TICKER_ENDPOINTS = [
        'tickers' => self::TICKER_ENDPOINT,
        'detail' => self::TICKER_DETAIL_ENDPOINT,
    ];

    const REQUEST_HEADERS = ['Content-Type' => 'application/json'];

    const TICKER_INFO_SERIALIZATION_CONFIG = '/opt/app-root/src/config/serialization/polygon_ticker_info.yml';
    const TICKER_DETAIL_SERIALIZATION_CONFIG = '/opt/app-root/src/config/serialization/polygon_ticker_detail.yml';
    const TICKER_INFO_UNIQUE_KEY = 'ticker';
    const TICKER_INFO_ENTITY_CLASS = 'App\DTO\Brokerage\Polygon\PolygonTickerInfo';
    const TICKER_DETAIL_ENTITY_CLASS = 'App\DTO\Brokerage\Polygon\PolygonTickerDetail';
}
