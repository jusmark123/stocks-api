<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\EventSubscriber\Job;

use App\Entity\Job;
use App\Event\Job\JobProcessFailedEvent;
use App\Event\Job\JobPublishFailedEvent;
use App\Event\Job\JobReceiveFailedEvent;
use App\EventSubscriber\JobFailedEventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Phake;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class JobFailedEventSubscriberTest extends TestCase
{
    /**
     * @var JobFailedEventSubscriber
     */
    private $subscriber;

    /**
     * @Mock
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @Mock
     *
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @Mock
     *
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @Mock
     *
     * @var JobReceiveFailedEvent
     */
    private $eventReceiveFailed;

    /**
     * @Mock
     *
     * @var JobProcessFailedEvent
     */
    private $eventProcessFailed;

    /**
     * @Mock
     *
     * @var JobPublishFailedEvent
     */
    private $eventPublishFailed;

    /**
     * @Mock
     *
     * @var \Exception
     */
    private $exception;

    /**
     * @var array
     */
    private $jobMessage;

    /**
     * @Mock
     *
     * @var Job
     */
    private $job;

    /**
     * @Mock
     *
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);
        Phake::when($this->serializer)
            ->serializer(Phake::anyParameters())
            ->thenReturn('{"some":true');
    }

    /**
     * @covers \App\EventSubscriber\JobFailedEventSubscriber::jobReceiveFailed()
     */
    public function testJobRecieveFailedEvent()
    {
        Phake::when($this->eventReceiveFailed)->getException()->thenReturn($this->exception);
        Phake::when($this->eventReceiveFailed)->getMessage()->thenReturn($this->jobMessage);

        $this->subscriber->jobReceiveFailed($this->eventReceiveFailed);

        Phake::verify($this->logger)->error($this->eventReceiveFailed->getException()->getMessage(), [
           'exception' => $this->eventReceiveFailed->getException(),
           'job_message' => ['some' => true],
        ]);
    }

    /**
     * @covers \App\EventSubscriber\JobFailedEventSubscriber::jobProcessFailed()
     */
    public function testJobProcessFailed()
    {
        Phake::when($this->eventProcessFailed)->getException()->thenReturn($this->exception);
        Phake::when($this->eventProcessFailed)->getMessage()->thenReturn($this->jobMessage);
        Phake::when($this->eventProcessFailed)->getJob()->thenReturn($this->job);
        Phake::when($this->job)->getId()->thenReturn('id');

        $this->subscriber->jobProcessFailed($this->eventProcessFailed);

        Phake::verify($this->logger)->error($this->eventProcessFailed->getException()->getMessage(), [
           'exception' => $this->eventProcessFailed->getException(),
           'job_message' => ['some' => true],
           'job' => ['some' => true],
           'jobUUID' => $this->eventProcessFailed->getJob()->getId(),
        ]);
    }

    /**
     * @covers \App\EventSubscriber\Job\JobFailedEventSubscriber::jobPublishFailed()
     */
    public function testJobPublishFailed()
    {
        Phake::when($this->eventPublishFailed)->getException()->thenReturn($this->exception);
        Phake::when($this->eventPublishFailed)->getJob()->thenReturn($this->job);
        Phake::when($this->job)->getId()->thenReturn('id');

        $this->subscriber->jobPublishFailed($this->eventPublishFailed);

        Phake::verify($this->logger)->error($this->eventProcessFailed->getException()->getMessage(), [
           'exception' => $this->eventPublishFailed->getException(),
           'job' => $this->eventPublishFailed->getJob(),
           'jobUUID' => $this->job->getId(),
        ]);
    }
}
