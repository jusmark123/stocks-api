<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\AccountStatusType;

/**
 * Class AccountStatusTypeEntityManager.
 */
class AccountStatusTypeEntityManager extends EntityManager
{
    const ENTITY_CLASS = AccountStatusType::class;
}
