<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\DTO\Brokerage\Alpaca\AccountInfo;

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
