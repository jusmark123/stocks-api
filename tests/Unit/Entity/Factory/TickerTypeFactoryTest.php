<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Factory;

use App\Entity\Factory\TickerTypeFactory;
use App\Entity\TickerType;
use PHPUnit\Framework\TestCase;

class TickerTypeFactoryTest extends TestCase
{
    /**
     * @covers \App\Entity\Factory\TickerTypeFactory::create()
     */
    public function testCreate()
    {
        $this->assertInstanceOf(TickerType::class, TickerTypeFactory::create());
    }
}
