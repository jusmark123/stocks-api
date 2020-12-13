<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Service\Message;

use App\Entity\Factory\JobFactory;
use App\Event\Job\JobReceivedEvent;
use App\Event\LoggerEvent;
use App\Helper\ValidationHelper;
use App\JobHandler\JobHandlerProvider;
use App\JobHandler\Order\SyncOrderHistoryJobHandler;
use App\Message\Factory\JobMessageFactory;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Protocol\MessageFactory;
use App\MessageClient\Protocol\Packet;
use App\Service\DefaultTypeService;
use App\Service\JobService;
use App\Service\Message\JobMessageService;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Phake;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class JobMessageServiceTest extends TestCase
{
    /**
     * @var JobMessageService
     */
    private $SUT;

    /**
     * @Mock
     *
     * @var Connection
     */
    private $connection;

    /**
     * @Mock
     *
     * @var JobHandlerProvider
     */
    private $jobHandlerProvider;

    /**
     * @Mock
     *
     * @var JobService
     */
    private $jobService;

    /**
     * @Mock
     *
     * @var DefaultTypeService
     */
    private $defaultTypeService;

    /**
     * @Mock
     *
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @Mock
     *
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * @Mock
     *
     * @var SyncOrderHistoryJobHandler
     */
    private $jobHandler;

    /**
     * @Mock
     *
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @Mock
     *
     * @var LoggerInterface
     */
    private $logger;

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
     * @var ValidationHelper
     */
    private $validator;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);

        Phake::when($this->connection)
            ->close()
            ->thenReturn($this->container);

        Phake::when($this->connection)
            ->connect()
            ->thenReturn(true);

        $this->SUT = Phake::partialMock(JobMessageService::class,
            $this->defaultTypeService,
            $this->entityManager,
            $this->dispatcher,
            $this->jobHandlerProvider,
            $this->jobService,
            $this->messageFactory,
            $this->logger,
            $this->publisher,
            $this->validator
        );
    }

    public function testConstructor()
    {
        $this->assertInstanceOf(JobMessageService::class, $this->SUT);
    }

    /**
     * @covers \JobMessageService::receive()
     */
    public function testReceive()
    {
        $event = new LoggerEvent('string');
        $job = JobFactory::create()
            ->setName('string');

        Phake::when($this->dispatcher)
            ->dispatch(Phake::anyParameters())
            ->thenReturn($event);

        Phake::when($this->packet)
            ->getMessage()
            ->thenReturn(serialize($job));

        Phake::when($this->jobHandlerProvider)
            ->getJobHandler(Phake::anyParameters())
            ->thenReturn($this->jobHandler);

        $this->SUT->receive($this->packet);

        Phake::verify($this->SUT)
            ->preReceive(Phake::anyParameters());
        Phake::verify($this->jobHandler)
            ->prepare($job);
        Phake::verify($this->dispatcher)
            ->dispatch(new JobReceivedEvent($job));
        Phake::verify($this->SUT)
            ->postReceive(Phake::anyParameter());
    }

    /**
     * @covers \JobMessageService::receive()
     */
    public function testReceiveException()
    {
        $event = new LoggerEvent('string');
        $job = JobFactory::create()
            ->setName('string');

        Phake::when($this->dispatcher)
            ->dispatch(Phake::anyParameters())
            ->thenReturn($event);

        Phake::when($this->packet)
            ->getMessage()
            ->thenReturn(serialize($job));

        Phake::when($this->jobHandlerProvider)
            ->getJobHandler(Phake::anyParameters())
            ->thenThrow(new \Exception('Error Occurred'));

        $this->SUT->receive($this->packet);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error Occurred');
    }
}
