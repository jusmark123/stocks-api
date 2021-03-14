<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\Order;
use App\Entity\OrderLog;

/**
 * Class OrderLogFactory.
 */
class OrderLogFactory extends AbstractFactory
{
    /**
     * @return OrderLog
     */
    public static function init(): OrderLog
    {
        return new OrderLog();
    }

    /**
     * @param Order $order
     *
     * @return OrderLog
     */
    public static function createFromOrder(Order $order): OrderLog
    {
        return self::init()
            ->setOrder($order)
            ->setAmountUsd($order->getAmountUsd())
            ->setOrderStatus($order->getOrderStatus())
            ->setFilledQuantity($order->getQuantityFilled());
    }
}
