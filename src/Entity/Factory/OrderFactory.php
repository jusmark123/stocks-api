<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\Order;

/**
 * Class OrderFactory.
 */
class OrderFactory extends AbstractFactory
{
    public static function create(): Order
    {
        return new Order();
    }
}
