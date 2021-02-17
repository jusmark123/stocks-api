<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\Watchlist;

class WatchlistFactory extends AbstractFactory
{
    public static function create(): Watchlist
    {
        return new Watchlist();
    }
}
