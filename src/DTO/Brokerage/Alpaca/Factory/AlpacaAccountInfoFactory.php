<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca\Factory;

use App\DTO\Brokerage\Alpaca\Account;
use App\Entity\Factory\AbstractFactory;

class AlpacaAccountInfoFactory extends AbstractFactory
{
    /**
     * @return Account
     */
    public static function create(): Account
    {
        return new Account();
    }
}
