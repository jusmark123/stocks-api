<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\Order;

/**
 * Class OrderEntityManager.
 */
class OrderEntityManager extends EntityManager
{
    const ENTITY_CLASS = Order::class;
}
