<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Interfaces\CreatedAtInterface;
use App\Entity\Interfaces\CreatedByInterface;
use App\Entity\Interfaces\ModifiedAtInterface;
use App\Entity\Interfaces\ModifiedByInterface;
use App\Entity\Traits as Traits;

abstract class AbstractDefaultEntity extends AbstractEntity implements CreatedAtInterface, CreatedByInterface, ModifiedAtInterface, ModifiedByInterface
{
    use Traits\CreatedAtTrait;
    use Traits\CreatedByTrait;
    use Traits\DeactivatedAtTrait;
    use Traits\DeactivatedByTrait;
    use Traits\EntityIdTrait;
    use Traits\ModifiedAtTrait;
    use Traits\ModifiedByTrait;
}
