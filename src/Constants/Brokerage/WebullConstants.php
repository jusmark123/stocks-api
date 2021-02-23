<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Constants\Brokerage;

class WebullConstants
{
    const API_BASE_TRADE_ENDPOINT = 'https://tradeapi.webullbroker.com/api/trade';
    const API_BASE_INFO_ENDPOINT = 'https://infoapi.webull.com/api';
    const API_BASE_NEW_TRADE_URL = 'https://trade.webullfintech.com/api';
    const API_BASE_OPTIONS_ENDPOINT = 'https://quoteapi.webullbroker.com/api';
    const API_BASE_PAPER_ENDPOINT = 'https://act.webullbroker.com/webull-paper-center/api';
    const API_BASE_PAPER_FINTECH_ENDPOINT = 'https://act.webullfintech.com/webull-paper-center/api';
    const API_BASE_QUOTE_ENDPOINT = 'https://quoteapi.webullbroker.com/api';
    const API_BASE_US_TRADE_ENDPOINT = 'https://ustrade.webullfinance.com/api';
    const API_BASE_USER_ENDPOINT = 'https://userapi.webull.com/api';
    const API_BASE_USER_BROKER_ENDPOINT = 'https://userapi.webullbroker.com/api';

    const ACCOUNT_ENDPOINT = self::API_BASE_TRADE_ENDPOINT.'/v3/home/%s';
    const ACCOUNT_ACTIVITIES_ENDPOINT = self::API_BASE_US_TRADE_ENDPOINT.'/trade/v2/funds/%s/activities';
    const PAPER_ACCOUNT_ENDPOINT = self::API_BASE_PAPER_FINTECH_ENDPOINT.'/paper/1/acc/%s';
    const LOGIN_ENDPOINT = self::API_BASE_USER_ENDPOINT.'/passport/login/v5/account';

    const DEFAULT_ACCOUNT_TYPE = 2;
    const DEFAULT_GRADE = 1;
    const DEFAULT_DEVICE_NAME = 'default_string';
    const DEFAULT_REGION = 6;

    const PWD_SALT = 'wl_app-a&b@!423^';

    const ACCESS_TOKEN_CACHE_KEY = 'tokens:%s:access-token';
    const TRADE_TOKEN_CACHE_KEY = 'tokens:%s:trade-token';
    const REFRESH_TOKEN_CACHE_KEY = 'tokens:%s:refresh-token';

    const HEADERS = [
        'Accept' => '*/*',
        'Accept-Encoding' => 'gzip, deflate',
        'Content-Type' => 'application/json',
        'platform' => 'web',
        'ver' => '3.22.20',
        'User-Agent' => '*',
        'lzone' => 'dc_core_r001',
    ];
}
