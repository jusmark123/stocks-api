<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Factory;

use App\Entity\Factory\TickerFactory;
use App\Entity\Ticker;
use PHPUnit\Framework\TestCase;

class TickerFactoryTest extends TestCase
{
    /**
     * @covers \App\Entity\Factory\TickerFactory::create()
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Ticker::class, TickerFactory::create());
    }
}
