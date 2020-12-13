<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Message;

use ApiPlatform\Core\Exception\ItemNotFoundException;
use App\Entity\Job;
use App\Event\Job\JobReceivedEvent;
use App\Event\Job\JobReceiveFailedEvent;
use App\Exception\JobProcessException;
use App\Helper\ValidationHelper;
use App\JobHandler\JobHandlerProvider;
use App\JobHandler\JobHandlerProviderInterface;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Protocol\MessageFactory;
use App\MessageClient\Protocol\Packet;
use App\Service\DefaultTypeService;
use App\Service\JobService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class JobMessageService.
 */
class JobMessageService extends AbstractMessageService
{
    /**
     * @var JobHandlerProviderInterface
     */
    private $jobHandlerProvider;

    /**
     * @var JobService
     */
    private $jobService;

    /**
     * JobMessageService constructor.
     *
     * @param DefaultTypeService       $defaultTypeService
     * @param EntityManagerInterface   $entityManager
     * @param EventDispatcherInterface $dispatcher
     * @param JobHandlerProvider       $jobHandlerProvider
     * @param JobService               $jobService
     * @param MessageFactory           $messageFactory
     * @param LoggerInterface          $logger
     * @param ClientPublisher          $publisher
     * @param ValidationHelper         $validator
     */
    public function __construct(
        DefaultTypeService $defaultTypeService,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher,
        JobHandlerProvider $jobHandlerProvider,
        JobService $jobService,
        MessageFactory $messageFactory,
        LoggerInterface $logger,
        ClientPublisher $publisher,
        ValidationHelper $validator
    ) {
        $this->jobHandlerProvider = $jobHandlerProvider;
        $this->jobService = $jobService;
        parent::__construct(
            $defaultTypeService,
            $entityManager,
            $dispatcher,
            $logger,
            $messageFactory,
            $publisher,
            $validator);
    }

    /**
     * @param Packet $packet
     *
     * @throws JobProcessException
     * @throws \Throwable
     *
     * @return mixed|void
     */
    public function receive(Packet $packet)
    {
        $job = null;
        try {
            $this->preReceive('Job:Handler start receiving job message');
            $job = unserialize($packet->getMessage());
            $job = $this->entityManager
                ->getRepository(Job::class)
                ->findOneBy(['guid' => $job->getGuid()->toString()]);

            if (!$job instanceof Job) {
                throw new ItemNotFoundException();
            }

            $this->dispatch(new JobReceivedEvent($job));

            $jobHandler = $this->jobHandlerProvider->getJobHandler($job);
            $jobHandler->prepare($job);
        } catch (\Throwable $e) {
            $this->logError($job, $e);
            $this->dispatch(new JobReceiveFailedEvent($packet->getMessage(), $e, $job));
            throw $e;
        } finally {
            $this->postReceive('Job:Handler end receiving job message');
        }
    }
}
