<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Factory;

use App\Entity\Factory\JobFactory;
use App\Entity\Job;
use PHPUnit\Framework\TestCase;

class JobFactoryTest extends TestCase
{
    /**
     * @covers \App\Entity\Factory\JobFactory::create()
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Job::class, JobFactory::create());
    }
}
