<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\Order;

class OrderEntityManager extends AbstractEntityManager
{
    const ENTITY_CLASS = Order::class;
}
