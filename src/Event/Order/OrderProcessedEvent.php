<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Order;

class OrderProcessedEvent extends AbstractEvent
{
    const EVENT_NAME = 'order.processed';

    /**
     * @var Order
     */
    protected $order;

    /**
     * OrderProcessedEvent Constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }
}
