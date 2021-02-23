<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage;

use App\Entity\Account;

interface BrokerageAccountHistoryRequestInterface
{
    public function getAccount(): Account;

    public function getFilters(): array;

    public function getLimit(): int;
}
