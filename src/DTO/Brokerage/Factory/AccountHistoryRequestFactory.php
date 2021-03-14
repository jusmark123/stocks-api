<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Factory;

use App\DTO\Brokerage\AccountHistoryRequest;
use App\Entity\Account;
use App\Entity\Factory\AbstractFactory;

class AccountHistoryRequestFactory extends AbstractFactory
{
    public static function create(Account $account, array $parameters): AccountHistoryRequest
    {
        return new AccountHistoryRequest($account, $parameters);
    }
}
