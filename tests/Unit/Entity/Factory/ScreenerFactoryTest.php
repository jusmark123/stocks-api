<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Factory;

use App\Entity\Factory\ScreenerFactory;
use App\Entity\Screener;
use PHPUnit\Framework\TestCase;

class ScreenerFactoryTest extends TestCase
{
    /**
     * @covers \App\Entity\Factory\ScreenerFactory::create()
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Screener::class, ScreenerFactory::create());
    }
}
