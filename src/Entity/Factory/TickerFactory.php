<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\Ticker;

class TickerFactory
{
    public static function create(): Ticker
    {
        return new Ticker();
    }
}
