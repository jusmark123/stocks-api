<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\Algorithm;

class AlgorithmFactory
{
    /**
     * @return Algorithm
     */
    public static function create(): Algorithm
    {
        return new Algorithm();
    }
}
