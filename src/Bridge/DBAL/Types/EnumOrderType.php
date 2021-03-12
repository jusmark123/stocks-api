<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Bridge\DBAL\Types;

class EnumOrderType extends AbstractEnumType
{
    const MARKET = 'market';
    const LIMIT = 'limit';
    const STOP = 'stop';
    const STOP_LIMIT = 'stop_limit';
    const TRAILING_STOP = 'trailing_stop';

    protected string $name = 'enumOrderType';
    protected array $values = [
        self::LIMIT,
        self::MARKET,
        self::STOP,
        self::STOP_LIMIT,
        self::TRAILING_STOP,
    ];
}
