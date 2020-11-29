<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Constants\Brokerage;

final class AlpacaConstants
{
    // Api Version
    const API_VERSION = '/v2';

    // Api Endpoints
    const ACCOUNT_ENDPOINT = self::API_VERSION.'/account';

    // AlpacaAccountInfoEntity Class
    const ACCOUNT_INFO_ENTITY_CLASS = 'App\DTO\Brokerage\Alpaca\AlpacaAccountInfo';

    // Brokerage Name
    const BROKERAGE_NAME = 'Alpaca Trader';

    // Request Constants
    const REQUEST_HEADER_API_KEY = 'APCA-API-KEY-ID';
    const REQUEST_HEADER_API_SECRET_KEY = 'APCA-API-SECRET-KEY';
    const REQUEST_RETURN_DATA_TYPE = 'json';
}
