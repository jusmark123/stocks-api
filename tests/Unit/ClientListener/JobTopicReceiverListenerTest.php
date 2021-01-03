<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\ClientListener;

use App\ClientListener\JobTopicReceiverListener;
use App\Constants\Transport\JobConstants;
use App\Constants\Transport\Queue;
use App\MessageClient\ClientListener\Channel;
use App\MessageClient\Protocol\Packet;
use App\Service\Message\JobMessageService;
use Phake;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;
use React\Promise\CancellablePromiseInterface as Reject;
use React\Promise\ExtendedPromiseInterface as Promise;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class JobTopicReceiverListenerTest.
 */
class JobTopicReceiverListenerTest extends TestCase
{
    /**
     * @var JobTopicReceiverListener
     */
    protected $SUT;

    /**
     * @Mock
     *
     * @var Channel
     */
    protected $channel;

    /**
     * @Mock
     *
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @Mock
     *
     * @var JobMessageService
     */
    protected $jobMessageService;

    /**
     * @Mock
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @Mock
     *
     * @var LoopInterface
     */
    protected $loop;

    /**
     * @Mock
     *
     * @var Process
     */
    protected $process;

    /**
     * @Mock
     *
     * @var Packet
     */
    protected $packet;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);

        $this->SUT = Phake::partialMock(JobTopicReceiverListener::class,
            $this->dispatcher,
            $this->jobMessageService,
            $this->loop,
            $this->logger
        );

        Phake::when($this->SUT)
            ->getProcess(Phake::anyParameters())
            ->thenReturn($this->process);
    }

    /**
     * @covers \App\ClientListener\JobTopicReceiverListener::__construct
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(JobTopicReceiverListener::class, $this->SUT);
    }

    /**
     * @covers \App\ClientListener\JobTopicReceiverListener::getSubscribedTopics
     */
    public function testGetSubscribedTopics()
    {
        $this->assertEquals($this->SUT->getSubscribedTopics(), [JobConstants::JOB_REQUEST_ROUTING_KEY]);
    }

    /**
     * @covers \App\ClientListener\JobTopicReceiverListener::consume
     */
    public function testConsumeBackPublish()
    {
        Phake::when($this->packet)
            ->hasHeader(Queue::SYSTEM_PUBLISHER_HEADER_NAME)
            ->thenReturn(true);

        Phake::when($this->packet)
            ->getHeader(Queue::SYSTEM_PUBLISHER_HEADER_NAME)
            ->thenReturn(Queue::SYSTEM_PUBLISHER_NAME);

        Phake::when($this->jobMessageService)
            ->receive($this->packet)
            ->thenReturn(true);

        $this->assertInstanceOf(Promise::class,
            $this->SUT->consume($this->packet, $this->channel));

        Phake::verify($this->packet, Phake::never())->getMessage();
    }

    /**
     * @covers \App\ClientListener\JobTopicReceiverListener::consume
     */
    public function testConsumerException()
    {
        Phake::when($this->jobMessageService)
            ->receive($this->packet)
            ->thenThrow(new \Exception());

        $this->assertInstanceOf(Reject::class,
            $this->SUT->consume($this->packet, $this->channel)
        );
    }
}
