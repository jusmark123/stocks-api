<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\EventSubscriber;

use App\EventSubscriber\AbstractMessageEventSubscriber;
use Phake;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class AbstractMessageEventSubcriberTest.
 */
class AbstractMessageEventSubcriberTest extends TestCase
{
    /**
     * @Mock
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @Mock
     *
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var AbstractMessageEventSubscriber
     */
    private $subscriber;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);
        $this->subscriber = $this->getMockForAbstractClass(
            AbstractMessageEventSubscriber::class,
            [$this->logger, $this->serializer]
        );
    }

    /**
     * @covers \App\EventSubscriber\AbstractMessageEventSubscriber::__construct()
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(AbstractMessageEventSubscriber::class, $this->subscriber);
    }
}
