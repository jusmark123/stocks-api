<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\Brokerage;

/**
 * Class BrokerageFactory.
 */
class BrokerageFactory extends AbstractFactory
{
    public static function create(): Brokerage
    {
        return new Brokerage();
    }
}
