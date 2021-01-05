<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\DTO\Brokerage\TdAmeritrade\TdAmeritradeAccountInfo;

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
