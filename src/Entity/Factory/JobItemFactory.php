<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\JobItem;

class JobItemFactory extends AbstractFactory
{
    public static function create(): JobItem
    {
        return new JobItem();
    }
}
