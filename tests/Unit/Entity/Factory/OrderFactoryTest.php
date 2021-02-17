<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Factory;

use App\Entity\Factory\OrderFactory;
use App\Entity\Order;
use PHPUnit\Framework\TestCase;

class OrderFactoryTest extends TestCase
{
    /**
     * @covers \App\Entity\Factory\OrderFactory::create()
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Order::class, OrderFactory::create());
    }
}
