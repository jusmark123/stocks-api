<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Job;
use App\Entity\Order;
use App\Event\AbstractFailedEvent;

class OrderInfoProcessFailedEvent extends AbstractFailedEvent
{
    const EVENT_NAME = 'order-info.';

    /**
     * @var Job
     */
    protected $job;

    /**
     * @var Order|null
     */
    protected $order;

    /**
     * @var OrderInfoInterface|null
     */
    protected $orderInfo;

    /**
     * @var array
     */
    protected $orderInfoMessage;

    /**
     * OrderInfoProcessFailedEvent constructor.
     *
     * @param array                   $orderInfoMessage
     * @param \Exception              $exception
     * @param Job                     $job
     * @param Order|null              $order
     * @param OrderInfoInterface|null $orderInfo
     */
    public function __construct(
        array $orderInfoMessage,
        \Exception $exception,
        Job $job,
        ?Order $order = null,
        ?OrderInfoInterface $orderInfo = null
    ) {
        $this->job = $job;
        $this->order = $order;
        $this->orderInfo = $orderInfo;
        $this->orderInfoMessage = $orderInfoMessage;
        parent::__construct($exception);
    }

    /**
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }

    /**
     * @return OrderInfoInterface
     */
    public function getOrderInfo(): ?OrderInfoInterface
    {
        return $this->orderInfo;
    }

    /**
     * @return Order
     */
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    /**
     * @return array
     */
    public function getOrderInfoMessage(): array
    {
        return $this->orderInfoMessage;
    }
}
