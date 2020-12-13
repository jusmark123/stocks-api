<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Order;

trait OrderInfoFailedEventTrait
{
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
