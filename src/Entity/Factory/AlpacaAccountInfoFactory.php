<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\DTO\Brokerage\Alpaca\AlpacaAccountInfo;

class AlpacaAccountInfoFactory extends AbstractFactory
{
    /**
     * @return AlpacaAccountInfo
     */
    public static function create(): AlpacaAccountInfo
    {
        return new AlpacaAccountInfo();
    }
}
