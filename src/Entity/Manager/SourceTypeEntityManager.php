<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\SourceType;

class SourceTypeEntityManager extends AbstractEntityManager
{
    const ENTITY_CLASS = SourceType::class;
}
