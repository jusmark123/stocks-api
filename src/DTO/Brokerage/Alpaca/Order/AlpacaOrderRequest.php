<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca\Order;

use App\DTO\Brokerage\Interfaces\OrderRequestInterface;
use App\Entity\Order;

class AlpacaOrderRequest implements OrderRequestInterface
{
    /**
     * @var Order
     */
    private $order;

    /**
     *  @var string
     */
    private $symbol;

    /**
     * @var int
     */
    private $qty;

    /**
     * @var string
     */
    private $side;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $timeInForce;

    /**
     * @var float
     */
    private $limitPrice;

    /**
     * @var float
     */
    private $stopPrice;

    /**
     * @var float
     */
    private $trailPrice;

    /**
     * @var float
     */
    private $trailPercent;

    /**
     * @var bool
     */
    private $extendedHours;

    /**
     * @var string
     */
    private $clientOrderId;

    /**
     * @var string
     */
    private $orderClass;

    /**
     * @var AlpacaTakeProfit
     */
    private $takeProfit;

    /**
     * @var AlpacaStopLoss
     */
    private $stopLoss;

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @param Order $order
     *
     * @return AlpacaOrderRequest
     */
    public function setOrder(Order $order): AlpacaOrderRequest
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     *
     * @return AlpacaOrderRequest
     */
    public function setSymbol(string $symbol): AlpacaOrderRequest
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * @return int
     */
    public function getQty(): int
    {
        return $this->qty;
    }

    /**
     * @param int $qty
     *
     * @return AlpacaOrderRequest
     */
    public function setQty(int $qty): AlpacaOrderRequest
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * @return string
     */
    public function getSide(): string
    {
        return $this->side;
    }

    /**
     * @param string $side
     *
     * @return AlpacaOrderRequest
     */
    public function setSide(string $side): AlpacaOrderRequest
    {
        $this->side = $side;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return AlpacaOrderRequest
     */
    public function setType(string $type): AlpacaOrderRequest
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getTimeInForce(): string
    {
        return $this->timeInForce;
    }

    /**
     * @param string $timeInForce
     *
     * @return AlpacaOrderRequest
     */
    public function setTimeInForce(string $timeInForce): AlpacaOrderRequest
    {
        $this->timeInForce = $timeInForce;

        return $this;
    }

    /**
     * @return float
     */
    public function getLimitPrice(): float
    {
        return $this->limitPrice;
    }

    /**
     * @param float $limitPrice
     *
     * @return $this
     */
    public function setLimitPrice(float $limitPrice): AlpacaOrderRequest
    {
        $this->limitPrice = $limitPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getStopPrice(): float
    {
        return $this->stopPrice;
    }

    /**
     * @param float $stopPrice
     *
     * @return $this
     */
    public function setStopPrice(float $stopPrice): AlpacaOrderRequest
    {
        $this->stopPrice = $stopPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getTrailPrice(): float
    {
        return $this->trailPrice;
    }

    /**
     * @param float $trailPrice
     *
     * @return AlpacaOrderRequest
     */
    public function setTrailPrice(float $trailPrice): AlpacaOrderRequest
    {
        $this->trailPrice = $trailPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getTrailPercent(): float
    {
        return $this->trailPercent;
    }

    /**
     * @param float $trailPercent
     *
     * @return AlpacaOrderRequest
     */
    public function setTrailPercent(float $trailPercent): AlpacaOrderRequest
    {
        $this->trailPercent = $trailPercent;

        return $this;
    }

    /**
     * @return bool
     */
    public function isExtendedHours(): bool
    {
        return $this->extendedHours;
    }

    /**
     * @param bool $extendedHours
     *
     * @return AlpacaOrderRequest
     */
    public function setExtendedHours(bool $extendedHours): AlpacaOrderRequest
    {
        $this->extendedHours = $extendedHours;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientOrderId(): string
    {
        return $this->clientOrderId;
    }

    /**
     * @param string $clientOrderId
     *
     * @return AlpacaOrderRequest
     */
    public function setClientOrderId(string $clientOrderId): AlpacaOrderRequest
    {
        $this->clientOrderId = $clientOrderId;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderClass(): string
    {
        return $this->orderClass;
    }

    /**
     * @param string $orderClass
     *
     * @return AlpacaOrderRequest
     */
    public function setOrderClass(string $orderClass): AlpacaOrderRequest
    {
        $this->orderClass = $orderClass;

        return $this;
    }

    /**
     * @return AlpacaTakeProfit
     */
    public function getTakeProfit(): AlpacaTakeProfit
    {
        return $this->takeProfit;
    }

    /**
     * @param AlpacaTakeProfit $takeProfit
     *
     * @return AlpacaOrderRequest
     */
    public function setTakeProfit(AlpacaTakeProfit $takeProfit): AlpacaOrderRequest
    {
        $this->takeProfit = $takeProfit;

        return $this;
    }

    /**
     * @return AlpacaStopLoss
     */
    public function getStopLoss(): AlpacaStopLoss
    {
        return $this->stopLoss;
    }

    /**
     * @param AlpacaStopLoss $stopLoss
     *
     * @return AlpacaOrderRequest
     */
    public function setStopLoss(AlpacaStopLoss $stopLoss): AlpacaOrderRequest
    {
        $this->stopLoss = $stopLoss;

        return $this;
    }
}
