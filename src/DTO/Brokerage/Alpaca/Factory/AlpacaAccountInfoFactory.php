<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca\Factory;

use App\DTO\Brokerage\Alpaca\AlpacaAccount;
use App\Entity\Factory\AbstractFactory;

class AlpacaAccountInfoFactory extends AbstractFactory
{
    /**
     * @return AlpacaAccount
     */
    public static function create(): AlpacaAccount
    {
        return new AlpacaAccount();
    }
}
