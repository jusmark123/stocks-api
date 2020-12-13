<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\JobDataItem;

class JobDataItemFactory extends AbstractFactory
{
    public static function create(): JobDataItem
    {
        return new JobDataItem();
    }
}
