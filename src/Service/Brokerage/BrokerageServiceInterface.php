<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\DTO\Brokerage\BrokerageAccountInterface;
use App\DTO\Brokerage\BrokerageOrderInterface;
use App\DTO\Brokerage\BrokerageTickerInterface;
use App\DTO\SyncOrdersRequest;
use App\DTO\SyncTickersRequest;
use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Job;
use App\Entity\Order;
use App\Entity\Ticker;
use Symfony\Component\Messenger\MessageBusInterface;

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
     * @return BrokerageAccountInterface|null
     */
    public function getAccountInfo(Account $account): ?BrokerageAccountInterface;

    /**
     * @param Account $account
     *
     * @return array|null
     */
    public function getPositions(Account $account): ?array;

    /**
     * @param BrokerageOrderInterface $orderInfo
     * @param Job                     $job
     *
     * @return Order
     */
    public function createOrderFromOrderInfo(BrokerageOrderInterface $orderInfo, Job $job): ?Order;

    /**
     * @param array $message
     *
     * @return BrokerageOrderInterface|null
     */
    public function createOrderInfoFromMessage(array $message): ?BrokerageOrderInterface;

    /**
     * @param SyncOrdersRequest   $request
     * @param MessageBusInterface $messageBus
     * @param Job                 $job
     *
     * @return Job|null
     */
    public function fetchOrderHistory(SyncOrdersRequest $request, MessageBusInterface $messageBus, Job $job): ?Job;

    /**
     * @param SyncTickersRequest  $request
     * @param MessageBusInterface $messageBus
     * @param Job                 $job
     *
     * @return Job|null
     */
    public function fetchTickers(SyncTickersRequest $request, MessageBusInterface $messageBus, Job $job): ?Job;

    /**
     * @param array $message
     *
     * @return BrokerageTickerInterface|null
     */
    public function createTickerInfoFromMessage(array $message): ?BrokerageTickerInterface;

    /**
     * @param BrokerageTickerInterface $tickerInfo
     * @param Job                      $job
     *
     * @return Ticker|null
     */
    public function createTickerFromTickerInfo(BrokerageTickerInterface $tickerInfo, Job $job): ?Ticker;

    /**
     * @return string
     */
    public function getConstantsClass(): string;
}
