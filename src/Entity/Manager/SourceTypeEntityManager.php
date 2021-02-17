<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\SourceType;

/**
 * Class SourceTypeEntityManager.
 */
class SourceTypeEntityManager extends EntityManager
{
    const ENTITY_CLASS = SourceType::class;
}
