<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\Source;

class SourceEntityManager extends AbstractEntityManager
{
    const ENTITY_CLASS = Source::class;
}
