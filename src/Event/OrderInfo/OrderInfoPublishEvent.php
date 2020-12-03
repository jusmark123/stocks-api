<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Event\AbstractEvent;

/**
 * Class OrderInfoPublishEvent.
 */
class OrderInfoPublishEvent extends AbstractEvent
{
    const EVENT_NAME = 'order-info.published';

    /** @var array */
    private $orderInfoMessage;

    /**
     * @var OrderInfoInterface
     */
    private $orderInfo;

    /**
     * OrderInfoPublishEvent constructor.
     *
     * @param array                   $orderInfoMessage
     * @param OrderInfoInterface|null $orderInfo
     */
    public function __construct(array $orderInfoMessage, ?OrderInfoInterface $orderInfo = null)
    {
        $this->orderInfoMessage = $orderInfoMessage;
        $this->orderInfo = $orderInfo;
    }

    /**
     * @return array
     */
    public function getOrderInfoMessage(): array
    {
        return $this->orderInfoMessage;
    }

    /**
     * @return OrderInfoInterface
     */
    public function getOrderInfo(): OrderInfoInterface
    {
        return $this->orderInfo;
    }
}
