<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\Source;

class SourceEntityManager extends EntityManager
{
    const ENTITY_CLASS = Source::class;
    const SOURCE_NOT_FOUND = 'Source Not Found';
}
