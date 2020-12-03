<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Constants\Entity;

final class OrderTypeConstants
{
    const MARKET_ORDER = 1;
    const LIMIT_ORDER = 2;
    const STOP_ORDER = 3;
    const STOP_LIMIT = 4;
    const TRAILING_STOP = 5;

    const NAMES = [
        self::MARKET_ORDER => 'market',
        self::LIMIT_ORDER => 'limit',
        self::STOP_ORDER => 'stop',
        self::STOP_LIMIT => 'stop_limit',
        self::TRAILING_STOP => 'trailing_stop',
     ];

    public static function getTypes()
    {
        return self::NAMES;
    }
}
