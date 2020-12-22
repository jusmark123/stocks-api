<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Constants\Entity;

/**
 * Class AccountStatusTypeConstants.
 */
final class AccountStatusTypeConstants
{
    const ACTIVE = 1;
    const INACTIVE = 2;

    const NAMES = [
        self::ACTIVE => 'ACTIVE',
        self::INACTIVE => 'INACTIVE',
    ];

    /**
     * @return string[]
     */
    public static function getTypes()
    {
        return self::NAMES;
    }
}
