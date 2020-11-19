<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\User;

/**
 * Class UserFactory.
 */
class UserFactory extends AbstractFactory
{
    public static function create(): User
    {
        return new User();
    }
}
