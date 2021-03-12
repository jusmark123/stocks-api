<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\DTO\Brokerage\AccountHistoryInterface;
use App\DTO\Brokerage\AccountHistoryRequestInterface;
use App\DTO\Brokerage\AccountInterface;
use App\DTO\Brokerage\Alpaca\AlpacaAccountConfiguration;
use App\Entity\Account;
use App\Entity\Brokerage;

/**
 * Interface BrokerageServiceInterface.
 */
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
     * @return AlpacaAccountConfiguration|null
     */
    public function fetchAccountConfiguration(Account $account): ?AlpacaAccountConfiguration;

    /**
     * @param Account $account
     *
     * @return AccountInterface|null
     */
    public function fetchAccountInfo(Account $account): ?AccountInterface;

    /**
     * @param AccountHistoryRequestInterface $request
     *
     * @return AccountInterface|null
     */
    public function fetchAccountHistory(AccountHistoryRequestInterface $request): ?AccountHistoryInterface;

    /**
     * @param Account $account
     *
     * @return array|null
     */
    public function fetchPositions(Account $account): ?array;

    /**
     * @param Account $account
     *
     * @return array|null
     */
    public function fetchOrders(Account $account): ?array;

    /**
     * @return string
     */
    public function getConstantsClass(): string;
}
