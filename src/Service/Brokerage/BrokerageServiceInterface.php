<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\DTO\Brokerage\AccountInfoInterface;
use App\DTO\Brokerage\OrderInfoInterface;
use App\DTO\Brokerage\TickerInterface;
use App\DTO\SyncOrdersRequest;
use App\DTO\SyncTickersRequest;
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
     * @param OrderInfoInterface $orderInfo
     * @param Job                $job
     *
     * @return Order
     */
    public function createOrderFromOrderInfo(OrderInfoInterface $orderInfo, Job $job): ?Order;

    /**
     * @param array $message
     *
     * @return OrderInfoInterface|null
     */
    public function createOrderInfoFromMessage(array $message): ?OrderInfoInterface;

    /**
     * @param SyncOrdersRequest $request
     * @param Job|null          $job
     *
     * @return Job|null
     */
    public function fetchOrderHistory(SyncOrdersRequest $request, Job $job): ?Job;

    /**
     * @param SyncTickersRequest $request
     * @param Job                $job
     *
     * @return Job|null
     */
    public function fetchTickers(SyncTickersRequest $request, Job $job): ?Job;

    /**
     * @param array $message
     *
     * @return TickerInterface|null
     */
    public function createTickerInfoFromMessage(array $message): ?TickerInterface;

    /**
     * @param TickerInterface $tickerInfo
     * @param Job             $job
     *
     * @return Ticker|null
     */
    public function createTickerFromTickerInfo(TickerInterface $tickerInfo, Job $job): ?Ticker;

    /**
     * @return string
     */
    public function getConstantsClass(): string;
}
