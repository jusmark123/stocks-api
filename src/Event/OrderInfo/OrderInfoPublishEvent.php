<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Job;
use App\Event\AbstractJobEvent;

/**
 * Class OrderInfoPublishEvent.
 */
class OrderInfoPublishEvent extends AbstractJobEvent
{
    const EVENT_NAME = 'order-info.publish';

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
    public function __construct(array $orderInfoMessage, Job $job, ?OrderInfoInterface $orderInfo = null)
    {
        $this->orderInfoMessage = $orderInfoMessage;
        $this->orderInfo = $orderInfo;
        parent::__construct($job);
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
