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
    const ORDERS_ENDPOINT = self::API_VERSION.'/orders';

    // AlpacaAccountInfoEntity Class
    const ACCOUNT_INFO_ENTITY_CLASS = 'App\DTO\Brokerage\Alpaca\AlpacaAccountInfo';

    // AlpacaOrderInfoEntity Class
    const ORDER_INFO_ENTITY_CLASS = 'App\DTO\Brokerage\Alpaca\Order\AlpacaOrderInfo';

    // Brokerage Name
    const BROKERAGE_NAME = 'Alpaca Trader';

    // Request Constants
    const REQUEST_HEADER_API_KEY = 'APCA-API-KEY-ID';
    const REQUEST_HEADER_API_SECRET_KEY = 'APCA-API-SECRET-KEY';
    const REQUEST_RETURN_DATA_TYPE = 'json';

    const ACCOUNT_INFO_SERIALIZATION_CONFIG = '/opt/app-root/src/config/serialization/alpaca_account_info.yml';
    const ORDER_INFO_SERIALIZATION_CONFIG = '/opt/app-root/src/config/serialization/alpaca_order_info.yml';

    // Filter Enums
    const ORDERS_STATUS_ENUM = ['open', 'closed', 'all'];
    const ORDERS_DIRECTION_ENUM = ['asc', 'desc'];

    const ORDERS_FILTERS_DATATYPE = [
        'nested' => FILTER_VALIDATE_BOOLEAN,
        'limit' => FILTER_VALIDATE_INT,
    ];
}
