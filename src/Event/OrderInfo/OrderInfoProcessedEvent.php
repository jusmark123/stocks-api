<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Job;
use App\Entity\Order;
use App\Event\AbstractEvent;

/**
 * Class OrderInfoProcessedEvent.
 */
class OrderInfoProcessedEvent extends AbstractEvent
{
    const EVENT_NAME = 'order-info.processed';

    /** @var OrderInfoInterface */
    protected $orderInfo;

    /** @var Order */
    protected $order;

    /** @var Job */
    protected $job;

    /**
     * OrderInfoProcessedEvent constructor.
     *
     * @param OrderInfoInterface $orderInfo
     * @param Order              $order
     * @param Job                $job
     */
    public function __construct(
        OrderInfoInterface $orderInfo,
        Order $order,
        Job $job
    ) {
        $this->job = $job;
        $this->order = $order;
        $this->orderInfo = $orderInfo;
    }

    /**
     * @return OrderInfoInterface
     */
    public function getOrderInfo(): OrderInfoInterface
    {
        return $this->orderInfo;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }
}
