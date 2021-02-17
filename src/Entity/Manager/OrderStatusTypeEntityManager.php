<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\OrderStatusType;

/**
 * Class OrderStatusTypeEntityManager.
 */
class OrderStatusTypeEntityManager extends EntityManager
{
    const ENTITY_CLASS = OrderStatusType::class;
}
