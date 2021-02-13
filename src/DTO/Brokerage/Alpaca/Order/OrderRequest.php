<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca\Order;

use App\DTO\Brokerage\OrderRequestInterface;
use App\Entity\Order;

class OrderRequest implements OrderRequestInterface
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
     * @var TakeProfit
     */
    private $takeProfit;

    /**
     * @var StopLoss
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
     * @return OrderRequest
     */
    public function setOrder(Order $order): OrderRequest
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
     * @return OrderRequest
     */
    public function setSymbol(string $symbol): OrderRequest
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
     * @return OrderRequest
     */
    public function setQty(int $qty): OrderRequest
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
     * @return OrderRequest
     */
    public function setSide(string $side): OrderRequest
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
     * @return OrderRequest
     */
    public function setType(string $type): OrderRequest
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
     * @return OrderRequest
     */
    public function setTimeInForce(string $timeInForce): OrderRequest
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
    public function setLimitPrice(float $limitPrice): OrderRequest
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
    public function setStopPrice(float $stopPrice): OrderRequest
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
     * @return OrderRequest
     */
    public function setTrailPrice(float $trailPrice): OrderRequest
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
     * @return OrderRequest
     */
    public function setTrailPercent(float $trailPercent): OrderRequest
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
     * @return OrderRequest
     */
    public function setExtendedHours(bool $extendedHours): OrderRequest
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
     * @return OrderRequest
     */
    public function setClientOrderId(string $clientOrderId): OrderRequest
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
     * @return OrderRequest
     */
    public function setOrderClass(string $orderClass): OrderRequest
    {
        $this->orderClass = $orderClass;

        return $this;
    }

    /**
     * @return TakeProfit
     */
    public function getTakeProfit(): TakeProfit
    {
        return $this->takeProfit;
    }

    /**
     * @param TakeProfit $takeProfit
     *
     * @return OrderRequest
     */
    public function setTakeProfit(TakeProfit $takeProfit): OrderRequest
    {
        $this->takeProfit = $takeProfit;

        return $this;
    }

    /**
     * @return StopLoss
     */
    public function getStopLoss(): StopLoss
    {
        return $this->stopLoss;
    }

    /**
     * @param StopLoss $stopLoss
     *
     * @return OrderRequest
     */
    public function setStopLoss(StopLoss $stopLoss): OrderRequest
    {
        $this->stopLoss = $stopLoss;

        return $this;
    }
}
