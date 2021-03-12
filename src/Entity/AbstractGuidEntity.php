<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits as Traits;

class AbstractGuidEntity extends AbstractDefaultEntity
{
    use Traits\EntityGuidTrait;
}
