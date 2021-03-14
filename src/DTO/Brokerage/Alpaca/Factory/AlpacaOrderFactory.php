<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca\Factory;

use App\DTO\Brokerage\Alpaca\Order\AlpacaOrder;
use App\DTO\Brokerage\BrokerageOrderInterface;
use App\Entity\Account;
use App\Entity\Factory\AbstractFactory;
use App\Entity\Factory\OrderFactory;
use App\Entity\Order;
use Ramsey\Uuid\Uuid;

/**
 * Class AlpacaOrderFactory.
 */
class AlpacaOrderFactory extends AbstractFactory
{
    /**
     * @return AlpacaOrder
     */
    public static function init(): AlpacaOrder
    {
        return new AlpacaOrder();
    }

    /**
     * @param BrokerageOrderInterface|AlpacaOrder $alpacaOrder
     * @param Account                             $account
     *
     * @return Order
     */
    public static function createOrder($alpacaOrder, Account $account): Order
    {
        return OrderFactory::create()
            ->setGuid(Uuid::fromString($alpacaOrder->getClientOrderId()))
            ->setAccount($account)
            ->setQuantityFilled($alpacaOrder->getFilledQty())
            ->setQuantity($alpacaOrder->getQty())
            ->setSide($alpacaOrder->getSide())
            ->setOrderSummary($alpacaOrder)
            ->setFilledAveragePrice($alpacaOrder->getFilledAvgPrice())
            ->setCreatedAt($alpacaOrder->getCreatedAt())
            ->setModifiedAt($alpacaOrder->getUpdatedAt());
    }

    /**
     * @param BrokerageOrderInterface|AlpacaOrder $alpacaOrder
     * @param Order                               $order
     *
     * @return Order
     */
    public static function updateOrder($alpacaOrder, Order $order): Order
    {
        return $order
            ->setQuantityFilled($alpacaOrder->getFilledQty())
            ->setOrderSummary($alpacaOrder)
            ->setFilledAveragePrice($alpacaOrder->getFilledAvgPrice())
            ->setModifiedAt($alpacaOrder->getUpdatedAt());
    }
}
