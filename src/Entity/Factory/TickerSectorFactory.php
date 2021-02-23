<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\TickerSector;

/**
 * Class TickerSectorFactory.
 */
class TickerSectorFactory extends AbstractFactory
{
    /**
     * @return TickerSector
     */
    public static function init(): TickerSector
    {
        return new TickerSector();
    }

    /**
     * @param string $name
     *
     * @return TickerSector
     */
    public static function create(string $name): TickerSector
    {
        return self::init()->setName($name);
    }
}
