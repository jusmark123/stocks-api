<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\Order;
use App\Entity\Position;

/**
 * Class AlpacaPositionFactory.
 */
class PositionFactory extends AbstractFactory
{
    /**
     * @return Position
     */
    public static function init(): Position
    {
        return new Position();
    }

    public static function createFromOrder(Order $order): Position
    {
        return self::init()
            ->setAccount($order->getAccount())
            ->setTicker($order->getTicker())
            ->setQuantity($order->getQuantityFilled())
            ->setAverageFilledPrice($order->getFilledAveragePrice())
            ->addOrder($order);
    }
}
