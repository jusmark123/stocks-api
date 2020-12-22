<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\EventSubscriber;

use App\EventSubscriber\AbstractEventSubscriber;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractEventSubscriberTest.
 */
class AbstractEventSubscriberTest extends TestCase
{
    /**
     * @var AbstractEventSubscriber
     */
    private $subscriber;

    protected function setUp()
    {
        $this->subscriber = $this->getMockForAbstractClass(
            AbstractEventSubscriber::class
        );
    }

    /**
     * @covers \App\EventSubscriber\AbstractEventSubscriber::getSubscribedEvents()
     */
    public function testGetSubscribedEvents()
    {
        $this->assertEquals([], $this->subscriber->getSubscribedEvents());
    }
}
