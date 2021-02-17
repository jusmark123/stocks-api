<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Factory;

use App\Entity\Brokerage;
use App\Entity\Factory\BrokerageFactory;
use PHPUnit\Framework\TestCase;

class BrokerageFactoryTest extends TestCase
{
    /**
     * @covers \App\Entity\Factory\BrokerageFactory::create()
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Brokerage::class, BrokerageFactory::create());
    }
}
