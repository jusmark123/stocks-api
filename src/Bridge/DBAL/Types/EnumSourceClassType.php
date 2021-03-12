<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Bridge\DBAL\Types;

use App\Entity\Algorithm;
use App\Entity\Screener;
use App\Entity\User;

class EnumSourceClassType extends AbstractEnumType
{
    const USER = User::class;
    const ALGORITHM = Algorithm::class;
    const SCREENER = Screener::class;

    protected string $name = 'enumSourceClassType';
    protected array $values = [
        self::ALGORITHM,
        self::SCREENER,
        self::USER,
    ];
}
