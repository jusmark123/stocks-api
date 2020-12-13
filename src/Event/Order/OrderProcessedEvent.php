<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Order;

use App\Entity\Job;
use App\Entity\Order;
use App\Event\AbstractJobEvent;

/**
 * Class OrderProcessedEvent.
 */
class OrderProcessedEvent extends AbstractJobEvent
{
    const EVENT_NAME = 'order.process';

    /**
     * @var Order
     */
    protected $order;

    /**
     * OrderProcessedEvent Constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order, Job $job)
    {
        $this->order = $order;
        parent::__construct($job);
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }
}
