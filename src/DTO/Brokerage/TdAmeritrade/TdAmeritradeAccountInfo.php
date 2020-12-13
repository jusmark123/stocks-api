<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\TdAmeritrade;

use App\DTO\Brokerage\AccountInfoInterface;
use App\Entity\Account;

/**
 * Class TdAmeritradeAccountInfo.
 */
class TdAmeritradeAccountInfo implements AccountInfoInterface
{
    /**
     * @var Account
     */
    private $account;
}
