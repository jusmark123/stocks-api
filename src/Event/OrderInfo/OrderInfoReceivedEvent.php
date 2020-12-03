<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Job;
use App\Event\AbstractEvent;

class OrderInfoReceivedEvent extends AbstractEvent
{
    const EVENT_NAME = 'order-info.reveived';

    /** @var Job */
    private $job;

    /** @var OrderInfoInterface */
    protected $orderInfoMessage;

    /**
     * OrderInfoReceivedEvent constructor.
     *
     * @param OrderInfoInterface $orderInfo
     */
    public function __construct(array $orderInfoMessage, Job $job)
    {
        $this->job = $job;
        $this->orderInfoMessage = $orderInfoMessage;
    }

    /**
     * @return OrderInfoInterface
     */
    public function getOrderInfoMessage(): OrderInfoInterface
    {
        return $this->orderInfoMessage;
    }

    /**
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }
}
