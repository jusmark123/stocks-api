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

    protected $name = 'enumSideType';
    protected $values = [self::LONG, self::SHORT];
}
