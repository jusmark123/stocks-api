<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\DTO\Brokerage\AccountInfoInterface;
use App\DTO\Brokerage\OrderInfoInterface;
use App\DTO\Brokerage\TickerInterface;
use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Job;
use App\Entity\Order;
use App\Entity\Ticker;

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
     * @param Account  $account
     * @param array    $filters
     * @param Job|null $job
     *
     * @return array
     */
    public function getOrderHistory(Account $account, array $filters, ?Job $job): ?Job;

    /**
     * @param OrderInfoInterface $orderInfo
     *
     * @return Order
     */
    public function createOrderFromOrderInfo(OrderInfoInterface $orderInfo): ?Order;

    /**
     * @param array $orderInfoOrderInfoMessage
     *
     * @return OrderInfoInterface|null
     */
    public function createOrderInfoFromMessage(array $orderInfoOrderInfoMessage): ?OrderInfoInterface;

    /**
     * @param TickerInterface $tickerInfo
     * @param Account         $account
     *
     * @return Ticker|null
     */
    public function createTickerFromTickerInfo(TickerInterface $tickerInfo, Account $account): ?Ticker;

    /**
     * @return string
     */
    public function getConstantsClass(): string;
}
