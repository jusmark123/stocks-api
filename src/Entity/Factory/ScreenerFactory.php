<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\Screener;

class ScreenerFactory
{
    /**
     * @return Screener
     */
    public static function create(): Screener
    {
        return new Screener();
    }
}
