<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Factory;

use App\Entity\Factory\TopicFactory;
use App\Entity\Topic;
use PHPUnit\Framework\TestCase;

class TopicFactoryTest extends TestCase
{
    /**
     * @covers \App\Entity\Factory\TopicFactory::create()
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Topic::class, TopicFactory::create());
    }
}
