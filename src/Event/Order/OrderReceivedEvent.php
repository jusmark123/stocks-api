<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Order;

use App\DTO\Interfaces\OrderRequestInterface;
use App\Event\AbstractEvent;

class OrderReceivedEvent extends AbstractEvent
{
    const EVENT_NAME = 'order.recieved';

    /**
     * @var OrderRequestInterface
     */
    protected $orderRequest;

    /**
     * OrderReceivedEvent Constructor.
     *
     * @param OrderRequestInterface $orderRequest
     */
    public function __construct(OrderRequestInterface $orderRequest)
    {
        $this->orderRequest = $orderRequest;
    }

    /**
     * @return OrderRequestInterface
     */
    public function getOrderRequest(): OrderRequestInterface
    {
        return $this->orderRequest;
    }
}
