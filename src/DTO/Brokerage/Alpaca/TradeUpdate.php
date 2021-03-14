<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca;

use App\DTO\Brokerage\Alpaca\Order\AlpacaOrder;
use App\DTO\Brokerage\BrokerageOrderEventInterface;
use App\DTO\Brokerage\BrokerageOrderInterface;

/**
 * Class TradeUpdate.
 */
class TradeUpdate implements BrokerageOrderEventInterface
{
    /**
     * @var string
     */
    private string $event;

    /**
     * @var float
     */
    private float $price = 0.00;

    /**
     * @var \DateTime
     */
    private \DateTime $timestamp;

    /**
     * @var float
     */
    private float $quantity = 0.00;

    /**
     * @var AlpacaOrder
     */
    private AlpacaOrder $order;

    /**
     * @return string
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * @param string $event
     *
     * @return TradeUpdate
     */
    public function setEvent(string $event): TradeUpdate
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float|string $price
     *
     * @return TradeUpdate
     */
    public function setPrice($price = 0.00): TradeUpdate
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * @param string|\DateTime $timestamp
     *
     * @return TradeUpdate
     */
    public function setTimestamp($timestamp): TradeUpdate
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * @param float|string $quantity
     *
     * @return TradeUpdate
     */
    public function setQuantity($quantity): TradeUpdate
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return AlpacaOrder
     */
    public function getOrder(): AlpacaOrder
    {
        return $this->order;
    }

    /**
     * @param AlpacaOrder|BrokerageOrderInterface $order
     *
     * @return TradeUpdate
     */
    public function setOrder(BrokerageOrderInterface $order): TradeUpdate
    {
        $this->order = $order;

        return $this;
    }
}
