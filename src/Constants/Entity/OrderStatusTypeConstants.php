<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Constants\Entity;

final class OrderStatusTypeConstants
{
    const NEW = 1;
    const CANCELLED = 2;
    const ERROR = 3;
    const PENDING = 4;

    //Alpaca AlpacaOrder Statuses
    const PARTIALLY_FILLED = 5;
    const FILLED = 6;
    const DONE_FOR_DAY = 7;
    const EXPIRED = 8;
    const REPLACED = 9;
    const PENDING_CANCEL = 10;
    const PENDING_REPLACE = 11;

    //Td Ameritrade AlpacaOrder Statuses
    const APPROVED = 12;
    const REJECTED = 13;
    const CANCEL = 14;

    const NAMES = [
        self::NEW => 'NEW',
        self::CANCELLED => 'CANCELLED',
        self::ERROR => 'ERROR',
        self::PENDING => 'PENDING',
        self::PARTIALLY_FILLED => 'PARTIALLY_FILLED',
        self::FILLED => 'FILLED',
        self::DONE_FOR_DAY => 'DONE_FOR_DAY',
        self::EXPIRED => 'EXPIRED',
        self::REPLACED => 'REPLACED',
        self::PENDING_CANCEL => 'PENDING_CANCEL',
        self::PENDING_REPLACE => 'PENDING_REPLACE',
        self::APPROVED => 'APPROVED',
        self::REJECTED => 'REJECTED',
        self::CANCEL => 'CANCEL',
    ];

    public static function getTypes()
    {
        return self::NAMES;
    }
}
