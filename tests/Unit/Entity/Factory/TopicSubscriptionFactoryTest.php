<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Factory;

use App\Entity\Factory\TopicFactory;
use App\Entity\Factory\TopicSubscriptionFactory;
use App\Entity\TopicSubscription;
use PHPUnit\Framework\TestCase;

class TopicSubscriptionFactoryTest extends TestCase
{
    /**
     * @covers \App\Entity\Factory\TopicSubscriptionFactory::create()
     * @covers \App\Entity\Factory\TopicSubscriptionFactory::init()
     */
    public function testCreate()
    {
        $topic = TopicFactory::create();
        $this->assertInstanceOf(TopicSubscription::class, TopicSubscriptionFactory::init());
        $this->assertInstanceOf(TopicSubscription::class, TopicSubscriptionFactory::create($topic));
    }
}
