<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\OrderType;

/**
 * Class OrderFactory.
 */
class OrderTypeFactory extends AbstractFactory
{
    public static function create(): OrderType
    {
        return new OrderType();
    }
}
