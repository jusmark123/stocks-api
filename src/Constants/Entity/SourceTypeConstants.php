<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Constants\Entity;

final class SourceTypeConstants
{
    const USER = 1;
    const ALGORITHM = 2;
    const SCRIPT = 3;
    const SYSTEM = 4;
    const SERVICE_ACCOUNT = 5;
    const SCHEDULER = 6;

    const NAMES = [
        self::USER => 'User',
        self::ALGORITHM => 'Algorithm',
        self::SCRIPT => 'Script',
        self::SYSTEM => 'System',
        self::SERVICE_ACCOUNT => 'Service AlpacaAccount',
        self::SCHEDULER => 'Scheduler',
    ];

    public static function getTypes()
    {
        return self::NAMES;
    }
}
