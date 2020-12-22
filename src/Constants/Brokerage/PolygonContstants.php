<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Constants\Brokerage;

final class PolygonContstants
{
    const API_KEY = 'PKU2P6NISHZELU5ATWEQ';
    const API_URL_BASE = 'https://api.polygon.io/v2';

    const URL_API_KEY_SUFFIX = '?apiKey='.self::API_KEY;
    const ALL_TICKERS_ENDPOINT = '/snapshot/locale/us/markets/stocks/tickers';
    const TICKER_ENDPOINT = '/reference/tickers';
    const TICKER_TYPE_ENDPOINT = '/reference/types';

    const REQUEST_HEADERS = ['Content-Type' => 'application/json'];
}
