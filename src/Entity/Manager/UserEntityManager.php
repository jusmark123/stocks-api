<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\User;

/**
 * Class UserEntityManager.
 */
class UserEntityManager extends EntityManager
{
    const ENTITY_CLASS = User::class;
}
