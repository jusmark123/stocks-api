<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\Position;

/**
 * Class PositionFactory.
 */
class PositionFactory extends AbstractFactory
{
    public static function create(): Position
    {
        return new Position();
    }
}
