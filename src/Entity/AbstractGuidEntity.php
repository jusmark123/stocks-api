<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Interfaces\EntityGuidInterface;
use App\Entity\Traits as Traits;

class AbstractGuidEntity extends AbstractDefaultEntity implements EntityGuidInterface
{
    use Traits\EntityGuidTrait;
}
