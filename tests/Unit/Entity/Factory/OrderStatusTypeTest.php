<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Factory;

use App\Entity\Factory\OrderStatusTypeFactory;
use App\Entity\OrderStatusType;
use PHPUnit\Framework\TestCase;

class OrderStatusTypeTest extends TestCase
{
    /**
     * @covers \App\Entity\Factory\OrderStatusTypeFactory::create()
     */
    public function testCreate()
    {
        $this->assertInstanceOf(OrderStatusType::class, OrderStatusTypeFactory::create());
    }
}
