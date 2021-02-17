<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Factory;

use App\Entity\Factory\OrderTypeFactory;
use App\Entity\OrderType;
use PHPUnit\Framework\TestCase;

class OrderTypeFactoryTest extends TestCase
{
    /**
     * @covers \App\Entity\Factory\OrderTypeFactory::create()
     */
    public function testCreate()
    {
        $this->assertInstanceOf(OrderType::class, OrderTypeFactory::create());
    }
}
