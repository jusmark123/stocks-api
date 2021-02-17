<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Factory;

use App\Entity\Factory\WatchlistFactory;
use App\Entity\Watchlist;
use PHPUnit\Framework\TestCase;

/**
 * Class WatchlistFactoryTest.
 */
class WatchlistFactoryTest extends TestCase
{
    /**
     * @covers \App\Entity\Factory\WatchlistFactory::create()
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Watchlist::class, WatchlistFactory::create());
    }
}
