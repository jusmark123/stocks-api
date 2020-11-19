<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage\Interfaces;

use App\Entity\Account;
use App\Entity\BrokerageOrderInterface;
use App\Entity\Order;

interface BrokerageInterface
{
    /**
     * @return BrokerageAccountInfoInterface
     */
    public function getAccountInfo(Account $account): AccountInfoInterface;

    public function getOrders(Account $account): BrokerageOrderInterface;

    public function getPositions(Account $account): BrokeragePositionInterface;

    public function createOrder(Order $order): Order;

    public function cancelOrder(Order $order): Order;

    public function updateOrder(Order $order): Order;
}
