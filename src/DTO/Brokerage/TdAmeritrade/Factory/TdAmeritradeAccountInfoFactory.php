<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\TdAmeritrade\Factory;

use App\DTO\Brokerage\TdAmeritrade\TdAmeritradeBrokerageAccount;
use App\Entity\Factory\AbstractFactory;

class TdAmeritradeAccountInfoFactory extends AbstractFactory
{
    /**
     * @return TdAmeritradeBrokerageAccount
     */
    public static function create(): TdAmeritradeBrokerageAccount
    {
        return new TdAmeritradeBrokerageAccount();
    }
}
