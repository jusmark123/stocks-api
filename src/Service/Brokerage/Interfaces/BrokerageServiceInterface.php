<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage\Interfaces;

use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Interfaces\AccountInfoInterface;

interface BrokerageServiceInterface
{
    /**
     * @param Brokerage $brokerage
     *
     * @return bool
     */
    public function supports(Brokerage $brokerage): bool;

    /**
     * @param Account $account
     *
     * @return AccountInfoInterface
     */
    public function getAccountInfo(Account $account): ?AccountInfoInterface;
}
