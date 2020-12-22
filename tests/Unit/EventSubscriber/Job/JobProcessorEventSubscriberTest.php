<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\EventSubscriber\Job;

use App\Entity\Job;
use App\Event\Job\JobProcessedEvent;
use App\Event\Job\JobPublishedEvent;
use App\Event\Job\JobPublishFailedEvent;
use App\Event\Job\JobReceivedEvent;
use App\EventSubscriber\Job\JobProcessorEventSubscriber;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Protocol\MessageFactory;
use App\MessageClient\Protocol\Packet;
use App\Service\JobService;
use Phake;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;

class JobProcessorEventSubscriberTest extends TestCase
{
    /**
     * @Mock
     *
     * @var JobReceivedEvent
     */
    private $jobReceivedEvent;

    /**
     * @Mock
     *
     * @var JobProcessedEvent
     */
    private $jobProcessedEvent;

    /**
     * @Mock
     *
     * @var JobPublishedEvent
     */
    private $jobPublishedEvent;

    /**
     * @Mock
     *
     * @var MessageFactory
     */
    private $factory;

    /**
     * @Mock
     *
     * @var Job
     */
    private $job;

    /**
     * @Mock
     *
     * @var JobService
     */
    private $jobService;

    /**
     * @Mock
     *
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @Mock
     *
     * @var Packet
     */
    private $packet;

    /**
     * @Mock
     *
     * @var ClientPublisher
     */
    private $publisher;

    /**
     * @Mock
     *
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @Mock
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var JobProcessorEventSubscriber
     */
    private $subscriber;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);
        Phake::when($this->factory)->createPacket(Phake::anyParameters())->thenReturn($this->packet);
        Phake::when($this->jobService)->save(Phake::anyParameters())->thenReturn($this->job);

        $this->subscriber = new JobProcessorEventSubscriber(
            $this->publisher,
            $this->dispatcher,
            $this->jobService,
            $this->logger,
            $this->factory,
            $this->serializer
        );
    }

    /**
     * @covers \App\EventSubscriber\Job\JobProcessorEventSubscriber::__constructor()
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(JobProcessorEventSubscriber::class, $this->subscriber);
    }

    /**
     * @covers  \App\EventSubscriber\Job\JobProcessorEventSubscriber::getSubscribedEvents()
     */
    public function testGetSubscriberEvent()
    {
        $this->assertGreaterThan(0, \count($this->subscriber->getSubscribedEvents()));
    }

    public function testPublish()
    {
        Phake::verify($this->publisher)->publish(Phake::anyParameters());
        Phake::verify($this->logger)->info(Phake::anyParameters());
    }

    public function testPublishException()
    {
        $exception = new \Exception('This is a test exception.');
        Phake::when($this->factory)
            ->createPacket(Phake::anyParameters())
            ->thenThrow($exception);
        Phake::verify($this->dispatcher)->dispatch(new JobPublishFailedEvent($this->job, $exception));
    }
}
