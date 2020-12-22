<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\DTO\Brokerage\Interfaces\AccountInfoInterface;
use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Order;

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
     * @return AccountInfoInterface|null
     */
    public function getAccountInfo(Account $account): ?AccountInfoInterface;

    /**
     * @param OrderInfoInterface $orderInfo
     *
     * @return Order
     */
    public function createOrderFromOrderInfo(OrderInfoInterface $orderInfo): ?Order;

    /**
     * @param Account $account
     * @param array   $orderInfoMessage
     *
     * @return OrderInfoInterface|null
     */
    public function createOrderInfoFromMessage(array $orderInfoMessage): ?OrderInfoInterface;

    /**
     * @return string
     */
    public function getConstantsClass(): string;
}
