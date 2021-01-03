<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\TdAmeritrade;

use App\Constants\Brokerage\TdAmeritradeConstants;
use App\DTO\Brokerage\AccountInfoInterface;

/**
 * Class TdAmeritradeAccountInfo.
 */
class TdAmeritradeAccountInfo implements AccountInfoInterface
{
    /**
     * @var string
     */
    private $brokerage;

    /**
     * @return mixed
     */
    public function getBrokerage()
    {
        return TdAmeritradeConstants::BROKERAGE_CONTEXT;
    }
}
