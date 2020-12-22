<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\Ticker;

class TickerEntityManager extends AbstractEntityManager
{
    const ENTITY_CLASS = Ticker::class;
}
