<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage\Interfaces;

use App\DTO\Brokerage\Interfaces\AccountInfoInterface;
use App\Entity\Account;
use App\Entity\Brokerage;

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
     * @throws \Psr\Http\Client\ClientExceptionInterface
     *
     * @return AccountInfoInterface|null
     */
    public function getAccountInfo(Account $account): ?AccountInfoInterface;

    /**
     * @return string
     */
    public function getConstantsClass(): string;
}
