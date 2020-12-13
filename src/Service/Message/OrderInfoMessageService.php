<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Message;

use ApiPlatform\Core\Exception\ItemNotFoundException;
use App\Entity\JobDataItem;
use App\Event\OrderInfo\OrderInfoReceivedEvent;
use App\Event\OrderInfo\OrderInfoReceiveFailedEvent;
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
use React\Promise as P;
use React\Promise\ExtendedPromiseInterface as Promise;
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
     * @param array $message
     */
    public function receive(Packet $packet): Promise
    {
        $jobDataItem = null;
        $job = null;
        try {
            $this->preReceive('Job:Handler start receiving job message');
            $jobDataItem = unserialize($packet->getMessage());
            $jobDataItem = $this->entityManager
                ->getRepository(JobDataItem::class)
                ->findOneBy(['guid' => $jobDataItem->getGuid()->toString()]);

            if (!$jobDataItem instanceof JobDataItem) {
                throw new ItemNotFoundException();
            }

            $job = $jobDataItem->getJob();

            $this->dispatch(new OrderInfoReceivedEvent($jobDataItem->getData(), $job));

            $jobHandler = $this->jobHandlerProvider->getJobHandler($job);
            $jobHandler->execute($jobDataItem, $job);

            return P\resolve();
        } catch (\Exception $e) {
            $this->dispatcher->dispatch(
                new OrderInfoReceiveFailedEvent($packet->getMessage(), $e, $job),
                OrderInfoReceiveFailedEvent::getEventName()
            );

            return P\reject($e);
        }
    }
}
