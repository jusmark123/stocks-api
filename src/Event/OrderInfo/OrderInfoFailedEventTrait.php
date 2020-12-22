<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Job;
use App\Entity\Order;

trait OrderInfoFailedEventTrait
{
    /**
     * @var Job
     */
    private $job;

    /**
     * @var Order|null
     */
    private $order;

    /**
     * @var OrderInfoInterface|null
     */
    protected $orderInfo;

    /**
     * @var array|null
     */
    private $orderInfoMessage;

    /**
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }

    /**
     * @return Order|null
     */
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    /**
     * @return OrderInfoInterface
     */
    public function getOrderInfo(): ?OrderInfoInterface
    {
        return $this->orderInfo;
    }

    /**
     * @return array
     */
    public function getOrderInfoMessage(): ?array
    {
        return $this->orderInfoMessage;
    }
}
