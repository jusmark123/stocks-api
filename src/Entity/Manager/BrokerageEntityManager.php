<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\Brokerage;

/**
 * Class BrokerageEntityManager.
 */
class BrokerageEntityManager extends EntityManager
{
    const ENTITY_CLASS = Brokerage::class;
}
