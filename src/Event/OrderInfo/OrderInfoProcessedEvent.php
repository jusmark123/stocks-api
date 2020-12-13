<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Job;
use App\Entity\Order;
use App\Event\AbstractJobEvent;

/**
 * Class OrderInfoProcessedEvent.
 */
class OrderInfoProcessedEvent extends AbstractJobEvent
{
    const EVENT_NAME = 'order-info.process';

    /** @var OrderInfoInterface */
    protected $orderInfo;

    /** @var Order */
    protected $order;

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
        $this->order = $order;
        $this->orderInfo = $orderInfo;
        parent::__construct($job);
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
}
