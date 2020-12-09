<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\TickerType;

class TickerTypeFactory
{
    public static function create(): TickerType
    {
        return new TickerType();
    }
}
