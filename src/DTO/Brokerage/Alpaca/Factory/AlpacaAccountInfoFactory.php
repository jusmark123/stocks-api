<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca\Factory;

use App\DTO\Brokerage\Alpaca\AccountInfo;
use App\Entity\Factory\AbstractFactory;

class AlpacaAccountInfoFactory extends AbstractFactory
{
    /**
     * @return AccountInfo
     */
    public static function create(): AccountInfo
    {
        return new AccountInfo();
    }
}
