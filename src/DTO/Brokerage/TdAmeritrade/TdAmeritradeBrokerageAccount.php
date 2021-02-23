<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\TdAmeritrade;

use App\Constants\Brokerage\TdAmeritradeConstants;
use App\DTO\Brokerage\BrokerageAccountInterface;

/**
 * Class TdAmeritradeBrokerageAccount.
 */
class TdAmeritradeBrokerageAccount implements BrokerageAccountInterface
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
