<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\EventSubscriber;

use App\Event\LoggerEvent;
use App\EventSubscriber\LoggerEventSubscriber;
use Monolog\Test\TestCase;
use Phake;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

/**
 * Class LoggerEventSubscriberTest.
 */
class LoggerEventSubscriberTest extends TestCase
{
    /**
     * @Mock
     *
     * @var LoggerEvent
     */
    private $loggerEvent;

    /**
     * @Mock
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @Mock
     *
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var LoggerEventSubscriber
     */
    private $subscriber;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);
        $this->subscriber = new LoggerEventSubscriber($this->logger, $this->dispatcher);
    }

    /**
     * @covers \App\EventSubscriber\LoggerEventSubscriber::__construct()
     */
    public function testConstuctor()
    {
        $this->assertInstanceOf(LoggerEventSubscriber::class, $this->loggerEvent);
    }

    /**
     * @covers \App\EventSubscriber\LoggerEventSubscriber::getSubscribedEvents()
     */
    public function testGetSubscriberEvents()
    {
        $this->assertGreaterThan(0, \count($this->subscriber->getSubscribedEvents()));
    }

    /**
     * @covers \App\EventSubscriber\LoggerEventSubscriber::setupDebugLogging()
     */
    public function testSetupDebugLogging()
    {
        Phake::when($this->dispatcher)->getListeners()->thenReturn(['switch.event' => 'SomeEventListener']);
        $this->subscriber->setupDebugLogging($this->loggerEvent);
        Phake::verify($this->dispatcher, Phake::times(2))->addListener(Phake::anyParameters());
        Phake::verify($this->dispatcher)->getListeners();
    }

    /**
     * @covers \App\EventSubscriber\LoggerEventSubscriber::info()
     */
    public function testInfo()
    {
        $this->subscriber->info($this->loggerEvent);
        Phake::verify($this->logger)->info($this->loggerEvent->getMessage());
    }
}
