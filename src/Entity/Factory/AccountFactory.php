<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\Account;

/**
 * Class AccountFactory.
 */
class AccountFactory extends AbstractFactory
{
    public static function create(): Account
    {
        return new Account();
    }
}
