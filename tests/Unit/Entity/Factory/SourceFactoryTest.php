<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Factory;

use App\Entity\Factory\SourceFactory;
use App\Entity\Source;
use PHPUnit\Framework\TestCase;

class SourceFactoryTest extends TestCase
{
    /**
     * @covers \App\Entity\Factory\SourceFactory::create()
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Source::class, SourceFactory::create());
    }
}
