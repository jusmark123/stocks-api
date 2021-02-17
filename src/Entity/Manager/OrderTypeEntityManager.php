<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\OrderType;

/**
 * Class OrderTypeEntityManager.
 */
class OrderTypeEntityManager extends EntityManager
{
    const ENTITY_CLASS = OrderType::class;
}
