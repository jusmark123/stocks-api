<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\Ticker;

class TickerEntityManager extends EntityManager
{
    const ENTITY_CLASS = Ticker::class;
}
