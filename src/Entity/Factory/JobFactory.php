<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\Job;

/**
 * Class JobFactory.
 */
class JobFactory extends AbstractFactory
{
    public static function create(): Job
    {
        return new Job();
    }
}
