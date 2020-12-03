<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Constants\Entity;

final class OrderStatusTypeConstants
{
    const COMPLETE = 1;
    const CANCELLED = 2;
    const ERROR = 3;
    const PENDING = 4;

    const NAMES = [
     self::PENDING => 'PENDING',
     self::CANCELLED => 'CANCELLED',
     self::COMPLETE => 'COMPLETE',
     self::ERROR => 'ERROR',
    ];

    public static function getTypes()
    {
        return self::NAMES;
    }
}
