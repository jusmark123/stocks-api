<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\TdAmeritrade\Factory;

use App\DTO\Brokerage\TdAmeritrade\TdAmeritradeAccountInfo;
use App\Entity\Factory\AbstractFactory;

class TdAmeritradeAccountInfoFactory extends AbstractFactory
{
    /**
     * @return TdAmeritradeAccountInfo
     */
    public static function create(): TdAmeritradeAccountInfo
    {
        return new TdAmeritradeAccountInfo();
    }
}
