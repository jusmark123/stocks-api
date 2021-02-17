<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Factory;

use App\Entity\Factory\JobItemFactory;
use App\Entity\JobItem;
use PHPUnit\Framework\TestCase;

class JobItemFactoryTest extends TestCase
{
    /**
     * @covers \App\Entity\Factory\JobItemFactory::create()
     */
    public function testCreate()
    {
        $this->assertInstanceOf(JobItem::class, JobItemFactory::create());
    }
}
