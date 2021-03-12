<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\TdAmeritrade;

use App\Constants\Brokerage\TdAmeritradeConstants;
use App\DTO\Brokerage\AccountInterface;

/**
 * Class TdAmeritradeAccount.
 */
class TdAmeritradeAccount implements AccountInterface
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
