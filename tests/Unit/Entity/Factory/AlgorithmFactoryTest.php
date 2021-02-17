<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Factory;

use App\Entity\Algorithm;
use App\Entity\Factory\AlgorithmFactory;
use PHPUnit\Framework\TestCase;

class AlgorithmFactoryTest extends TestCase
{
    /**
     * @covers \App\Entity\Factory\AlgorithmFactory::create()
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Algorithm::class, AlgorithmFactory::create());
    }
}
