<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\AccountStatusType;

/**
 * Class AccountStatusType Factory.
 */
class AccountStatusTypeFactory extends AbstractFactory
{
    public static function create(): AccountStatusType
    {
        return new AccountStatusType();
    }
}
