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

abstract class AbstractDefaultEntity implements CreatedAtInterface, CreatedByInterface, ModifiedAtInterface, ModifiedByInterface
{
    use Traits\EntityIdTrait;
    use Traits\CreatedAtTrait;
    use Traits\CreatedByTrait;
    use Traits\ModifiedByTrait;
    use Traits\ModifiedAtTrait;
    use Traits\DeactivatedAtTrait;
    use Traits\DeactivatedByTrait;
}
