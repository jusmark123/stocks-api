<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage;

use App\Entity\Account;

interface AccountHistoryRequestInterface
{
    public function getAccount(): Account;

    public function getParameters(): array;
}
