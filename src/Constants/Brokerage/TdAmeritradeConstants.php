<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Constants\Brokerage;

final class TdAmeritradeConstants
{
    const BROKERAGE_NAME = 'TD Ameritrade';

    const ACCOUNT_ENDPOINT = '';
    const ACCOUNT_INFO_ENTITY_CLASS = '';

    const REQUEST_RETURN_DATA_TYPE = '';

    const ACCOUNT_INFO_SERIALIZATION_CONFIG = '/opt/app-root/src/config/serialization/td_ameritrade_account_info.yml';
    const ORDER_INFO_SERIALIZATION_CONFIG = '/opt/app-root/src/config/serialization/td_ameritrade_order_info.yml';
}
