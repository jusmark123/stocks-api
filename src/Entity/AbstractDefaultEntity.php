<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits as Traits;

/**
 * Class AbstractDefaultEntity.
 */
abstract class AbstractDefaultEntity extends AbstractEntity
{
    use Traits\CreatedAtTrait;
    use Traits\DeactivatedAtTrait;
    use Traits\ModifiedAtTrait;
}
