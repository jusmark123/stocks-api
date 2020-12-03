<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Event\AbstractEvent;

class OrderInfoProcessedEvent extends AbstractEvent
{
    const EVENT_NAME = 'order-info.processed';
    /**
     * @var OrderInfoInterface
     */
    protected $orderInfo;

    /**
     * @param OrderInfoInterface $orderInfo
     */
    public function __construct(OrderInfoInterface $orderInfo)
    {
        $this->orderInfo = $orderInfo;
    }

    /**
     * @return OrderInfoInterface
     */
    public function getOrderInfo(): OrderInfoInterface
    {
        return $this->orderInfo;
    }
}
