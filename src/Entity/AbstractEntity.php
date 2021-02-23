<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Interfaces\EntityIdInterface;
use App\Entity\Interfaces\EntityInterface;
use App\Entity\Traits\EntityIdTrait;

/**
 * Class AbstractEntity.
 */
abstract class AbstractEntity implements EntityInterface, EntityIdInterface
{
    use EntityIdTrait;
}
