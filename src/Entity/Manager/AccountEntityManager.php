<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\Account;

class AccountEntityManager extends AbstractEntityManager
{
    const ENTITY_CLASS = Account::class;
}
