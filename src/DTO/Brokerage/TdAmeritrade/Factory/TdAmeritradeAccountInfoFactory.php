<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\TdAmeritrade\Factory;

use App\DTO\Brokerage\TdAmeritrade\TdAmeritradeAccount;
use App\Entity\Factory\AbstractFactory;

class TdAmeritradeAccountInfoFactory extends AbstractFactory
{
    /**
     * @return TdAmeritradeAccount
     */
    public static function create(): TdAmeritradeAccount
    {
        return new TdAmeritradeAccount();
    }
}
