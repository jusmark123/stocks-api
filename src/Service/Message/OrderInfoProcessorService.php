<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Message;

use App\Constants\Transport\JobConstants;
use App\Event\AbstractEvent;
use App\Event\OrderInfo\OrderInfoProcessedEvent;
use App\Event\OrderInfo\OrderInfoReceivedEvent;
use App\Helper\ValidationHelper;
use App\Service\AccountService;
use App\Service\JobService;
use App\Service\OrderService;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class OrderInfoProcessorService.
 */
class OrderInfoProcessorService extends AbstractMessageService
{
    /** @var AccountService */
    protected $accountService;

    /** @var JobService */
    protected $jobService;

    /** @var OrderService */
    protected $orderService;

    /**
     * OrderInfoProcessorService constructor.
     *
     * @param AccountService           $accountService
     * @param EventDispatcherInterface $dispatcher
     * @param JobService               $jobService
     * @param LoggerInterface          $logger
     * @param OrderService             $orderService
     * @param ValidationHelper         $validator
     */
    public function __construct(
        AccountService $accountService,
        EventDispatcherInterface $dispatcher,
        JobService $jobService,
        LoggerInterface $logger,
        OrderService $orderService,
        ValidationHelper $validator
    ) {
        $this->accountService = $accountService;
        $this->jobService = $jobService;
        $this->orderService = $orderService;
        parent::__construct($dispatcher, $logger, $validator);
    }

    /**
     * @return JobService
     */
    public function getJobService(): JobService
    {
        return $this->jobService;
    }

    /**
     * @return OrderService
     */
    public function getOrderService(): OrderService
    {
        return $this->orderService;
    }

    /**
     * @param AbstractEvent $event
     *
     * @return bool
     */
    public function process(OrderInfoReceivedEvent $event)
    {
        $job = $event->getJob();
        $orderInfoMessage = $event->getOrderInfoMessage();
        $orderInfo = $this->accountService->createOrderInfoFromMessage($job->getAccount(), $orderInfoMessage);
        $order = $this->orderService->createOrderFromOrderInfo($orderInfo);
        $data[$orderInfo->getId()] = JobConstants::JOB_PROCESSED;

        $job->setData($data);

        $this->jobService->save($job);

        $this->getDispatcher()->dispatch(
            new OrderInfoProcessedEvent($orderInfo, $order, $job),
            OrderInfoProcessedEvent::getEventName());
    }
}
