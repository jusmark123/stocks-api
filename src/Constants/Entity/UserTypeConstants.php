<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Constants\Entity;

class UserTypeConstants
{
    const SYSTEM_ADMIN = 1;
    const SERVICE_ACCOUNT = 2;
    const ACCOUNT_USER = 3;

    const NAMES = [
        self::SYSTEM_ADMIN => 'System Admin',
        self::SERVICE_ACCOUNT => 'Service Account',
        self::ACCOUNT_USER => 'User',
    ];

    public static function getTypes()
    {
        return self::NAMES;
    }
}
