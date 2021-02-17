<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\TickerType;

/**
 * Class TickerTypeEntityManager.
 */
class TickerTypeEntityManager extends EntityManager
{
    const ENTITY_CLASS = TickerType::class;
}
