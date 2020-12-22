<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\OrderStatusType;

/**
 * Class OrderStatusTypeFactory.
 */
class OrderStatusTypeFactory extends AbstractFactory
{
    public static function create(): OrderStatusType
    {
        return new OrderStatusType();
    }
}
