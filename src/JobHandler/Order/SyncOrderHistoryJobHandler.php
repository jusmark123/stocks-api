<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\JobHandler\Order;

use App\Constants\Transport\JobConstants;
use App\Constants\Transport\Queue;
use App\Entity\Job;
use App\Entity\JobDataItem;
use App\Event\OrderInfo\OrderInfoProcessedEvent;
use App\Exception\EmptyJobDataException;
use App\Exception\EmptyOrderHistoryException;
use App\JobHandler\AbstractJobHandler;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Exception\InvalidMessage;
use App\MessageClient\Exception\PublishException;
use App\MessageClient\Protocol\MessageFactory;
use App\Service\JobService;
use App\Service\OrderService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class SyncOrderHistoryJobHandler.
 */
class SyncOrderHistoryJobHandler extends AbstractJobHandler
{
    const JOB_NAME = 'sync-order-history';
    const JOB_DESCRIPTION = 'Sync account order history';
    const HEADERS = [
        Queue::SYSTEM_PUBLISHER_HEADER_NAME => Queue::SYSTEM_PUBLISHER_NAME,
        JobConstants::REQUEST_HEADER_NAME => self::JOB_NAME,
    ];

    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * SyncOrderHistoryJobHandler constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param JobService               $jobService
     * @param LoggerInterface          $logger
     * @param OrderService             $orderService
     * @param MessageFactory           $messageFactory
     * @param ClientPublisher          $publisher
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        JobService $jobService,
        LoggerInterface $logger,
        OrderService $orderService,
        MessageFactory $messageFactory,
        ClientPublisher $publisher
    ) {
        $this->orderService = $orderService;
        parent::__construct($dispatcher, $jobService, $logger, $messageFactory, $publisher);
    }

    /**
     * @param string      $jobName
     * @param string|null $resourceClass
     *
     * @return bool
     */
    public function supports(string $jobName, ?string $resourceClass = null): bool
    {
        return self::JOB_NAME === $jobName;
    }

    /**
     * @param Job $job
     *
     * @throws EmptyOrderHistoryException
     * @throws InvalidMessage
     * @throws PublishException
     *
     * @return bool|mixed|void
     */
    public function prepare(Job $job)
    {
        $jobData = $job->getJobData();

        if (null === $jobData || empty($jobData)) {
            throw new EmptyJobDataException();
        }

        foreach ($jobData as $jobDataItem) {
            $packet = $this->messageFactory->createPacket(
                Queue::ORDER_INFO_PERSISTENT_ROUTING_KEY,
                serialize($jobDataItem),
                self::HEADERS
            );
            $this->publisher->publish($packet);
        }
    }

    /**
     * @param JobDataItem $jobData
     * @param Job         $job
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @return bool|void
     */
    public function execute(JobDataItem $jobData, Job $job)
    {
        $account = $job->getAccount();
        $orderInfo = $this->orderService->createOrderInfoFromMessage($account, $jobData->getData())
            ->setSource($job->getSource());
        $order = $this->orderService->createOrderFromOrderInfo($account, $orderInfo);
        $this->dispatcher->dispatch(
            new OrderInfoProcessedEvent($orderInfo, $order, $job),
            OrderInfoProcessedEvent::getEventName()
        );
    }
}
