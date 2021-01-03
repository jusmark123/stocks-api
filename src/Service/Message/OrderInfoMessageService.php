<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Message;

use ApiPlatform\Core\Exception\ItemNotFoundException;
use App\Constants\Transport\JobConstants;
use App\Entity\Job;
use App\Entity\JobItem;
use App\Event\JobItem\JobItemCancelledEvent;
use App\Event\OrderInfo\OrderInfoReceivedEvent;
use App\Event\OrderInfo\OrderInfoReceiveFailedEvent;
use App\Exception\JobCancelledException;
use App\Helper\ValidationHelper;
use App\JobHandler\JobHandlerProvider;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Protocol\MessageFactory;
use App\MessageClient\Protocol\Packet;
use App\Service\DefaultTypeService;
use App\Service\JobService;
use App\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class OrderInfoMessageService.
 */
class OrderInfoMessageService extends AbstractMessageService
{
    /**
     * @var JobHandlerProvider
     */
    private $jobHandlerProvider;

    /**
     * @var JobService
     */
    private $jobService;

    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * OrderInfoMessageService constructor.
     *
     * @param DefaultTypeService       $defaultTypeService
     * @param EntityManagerInterface   $entityManager
     * @param EventDispatcherInterface $dispatcher
     * @param JobHandlerProvider       $jobHandlerProvider
     * @param LoggerInterface          $logger
     * @param MessageFactory           $messageFactory
     * @param OrderService             $orderService
     * @param ClientPublisher          $publisher
     * @param ValidationHelper         $validator
     */
    public function __construct(
        DefaultTypeService $defaultTypeService,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher,
        JobHandlerProvider $jobHandlerProvider,
        JobService $jobService,
        LoggerInterface $logger,
        MessageFactory $messageFactory,
        OrderService $orderService,
        ClientPublisher $publisher,
        ValidationHelper $validator
    ) {
        $this->jobService = $jobService;
        $this->jobHandlerProvider = $jobHandlerProvider;
        $this->orderService = $orderService;
        parent::__construct(
            $defaultTypeService,
            $entityManager,
            $dispatcher,
            $logger,
            $messageFactory,
            $publisher,
            $validator
        );
    }

    /**
     * @return OrderService
     */
    public function getOrderService(): OrderService
    {
        return $this->orderService;
    }

    /**
     * @param Packet $packet
     *
     * @return mixed|void
     */
    public function receive(Packet $packet)
    {
        $jobItem = null;
        $job = null;
        try {
            $this->preReceive('Job:Handler start receiving job message');
            $message = json_decode($packet->getMessage(), true);

            /** @var JobItem $jobItem */
            $jobItem = $this->entityManager
                ->getRepository(JobItem::class)
                ->findOneBy(['guid' => $message['jobItemUUID']]);

            /** @var Job $job */
            $job = $this->entityManager
                ->getRepository(Job::class)
                ->findOneBy(['guid' => $message['jobUUID']]);

            if (!$jobItem instanceof JobItem) {
                throw new ItemNotFoundException();
            }

            if (JobConstants::JOB_CANCELLED === $job->getStatus()) {
                $this->dispatch(new JobItemCancelledEvent($jobItem));
                throw new JobCancelledException();
            }

            $this->dispatch(new OrderInfoReceivedEvent($job, $jobItem));
            $jobHandler = $this->jobHandlerProvider->getJobHandler($job);
            $jobHandler->execute($jobItem, $job);
        } catch (ItemNotFoundException | JobCancelledException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->dispatcher->dispatch(
                new OrderInfoReceiveFailedEvent($e, $jobItem),
                OrderInfoReceiveFailedEvent::getEventName()
            );
            throw $e;
        }
    }
}
