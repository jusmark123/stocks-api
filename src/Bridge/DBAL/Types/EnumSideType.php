<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Bridge\DBAL\Types;

class EnumSideType extends AbstractEnumType
{
    const LONG = 'long';
    const SHORT = 'short';

    protected string $name = 'enumSideType';
    protected array $values = [self::LONG, self::SHORT];
}
