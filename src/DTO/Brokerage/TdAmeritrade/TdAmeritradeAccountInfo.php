<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\TdAmeritrade;

use App\DTO\Brokerage\Interfaces\AccountInfoInterface;
use App\Entity\Account;

class TdAmeritradeAccountInfo implements AccountInfoInterface
{
    /**
     * @var Account
     */
    private $account;
}
