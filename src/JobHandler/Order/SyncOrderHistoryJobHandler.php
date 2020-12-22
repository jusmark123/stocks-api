<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\JobHandler\Order;

use App\Constants\Transport\JobConstants;
use App\Constants\Transport\Queue;
use App\Entity\Job;
use App\Entity\JobItem;
use App\Event\Job\JobInitiatedEvent;
use App\Event\Job\JobInitiateFailedEvent;
use App\Event\OrderInfo\OrderInfoProcessedEvent;
use App\Event\OrderInfo\OrderInfoProcessFailedEvent;
use App\Exception\EmptyJobDataException;
use App\JobHandler\AbstractJobHandler;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Protocol\MessageFactory;
use App\Service\JobService;
use App\Service\OrderService;
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
     * @throws EmptyJobDataException
     *
     * @return mixed|void
     */
    public function prepare(Job $job)
    {
        try {
            $this->orderService->getOrderHistory($job->getAccount(), $job->getConfig(), $job);
        } catch (\Exception $e) {
            $this->dispatcher->dispatch(new JobInitiateFailedEvent($e, $job));
            throw $e;
        }
        $this->dispatcher->dispatch(new JobInitiatedEvent($job));
    }

    /**
     * @param JobItem $jobItem
     * @param Job     $job
     *
     * @return bool|void
     */
    public function execute(JobItem $jobItem, Job $job): bool
    {
        $order = null;
        $orderInfo = null;
        try {
            $this->jobItemProcessing($jobItem);

            $orderInfo = $this->orderService->createOrderInfoFromMessage($job->getAccount(), $jobItem->getData())
                ->setSource($job->getSource())
                ->setAccount($job->getAccount());

            $order = $this->orderService->createOrderFromOrderInfo($job->getAccount(), $orderInfo);

            $this->dispatcher->dispatch(
                new OrderInfoProcessedEvent($job, $jobItem, $orderInfo, $order),
                OrderInfoProcessedEvent::getEventName()
            );
        } catch (\Exception $e) {
            $this->dispatcher->dispatch(
                new OrderInfoProcessFailedEvent(
                    $e,
                    $jobItem,
                    $order,
                    $orderInfo
                ),
                OrderInfoProcessFailedEvent::getEventName()
            );

            return false;
        }

        return true;
    }
}
