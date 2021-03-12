<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Bridge\DBAL\Types;

class EnumPositionType extends AbstractEnumType
{
    const CRYPTO = 'crypto';
    const CURRENCY = 'currency';
    const EQUITY = 'equity';
    const INDEX = 'index';
    const OPTION = 'option';

    protected string $name = 'enumPositionType';
    protected array $values = [
        self::CRYPTO,
        self::CURRENCY,
        self::EQUITY,
        self::INDEX,
        self::OPTION,
    ];
}
