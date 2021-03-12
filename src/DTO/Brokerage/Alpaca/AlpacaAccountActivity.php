<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca;

use App\DTO\Brokerage\AccountHistoryInterface;
use App\Entity\Order;
use App\Entity\Ticker;

class AlpacaAccountActivity implements AccountHistoryInterface
{
    /**
     * @var float
     */
    private float $cumQty;

    /**
     * @var int
     */
    private int $leavesQty;

    /**
     * @var float
     */
    private float $price;

    /**
     * @var int
     */
    private int $qty;

    /**
     * @var string
     */
    private string $side;

    /**
     * @var Ticker
     */
    private Ticker $ticker;

    /**
     * @var Order|null
     */
    private ?Order $order;

    /**
     * @var string
     */
    private string $type;

    /**
     * @return float
     */
    public function getCumQty(): float
    {
        return $this->cumQty;
    }

    /**
     * @param float $cumQty
     *
     * @return AlpacaAccountActivity
     */
    public function setCumQty(float $cumQty): AlpacaAccountActivity
    {
        $this->cumQty = $cumQty;

        return $this;
    }

    /**
     * @return int
     */
    public function getLeavesQty(): int
    {
        return $this->leavesQty;
    }

    /**
     * @param int $leavesQty
     *
     * @return AlpacaAccountActivity
     */
    public function setLeavesQty(int $leavesQty): AlpacaAccountActivity
    {
        $this->leavesQty = $leavesQty;

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
     * @param float $price
     *
     * @return AlpacaAccountActivity
     */
    public function setPrice(float $price): AlpacaAccountActivity
    {
        $this->price = $price;

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
     * @return AlpacaAccountActivity
     */
    public function setQty(int $qty): AlpacaAccountActivity
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
     * @return AlpacaAccountActivity
     */
    public function setSide(string $side): AlpacaAccountActivity
    {
        $this->side = $side;

        return $this;
    }

    /**
     * @return Ticker
     */
    public function getTicker(): Ticker
    {
        return $this->ticker;
    }

    /**
     * @param Ticker $ticker
     *
     * @return AlpacaAccountActivity
     */
    public function setTicker(Ticker $ticker): AlpacaAccountActivity
    {
        $this->ticker = $ticker;

        return $this;
    }

    /**
     * @return Order|null
     */
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    /**
     * @param Order|null $order
     *
     * @return AlpacaAccountActivity
     */
    public function setOrder(?Order $order): AlpacaAccountActivity
    {
        $this->order = $order;

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
     * @return AlpacaAccountActivity
     */
    public function setType(string $type): AlpacaAccountActivity
    {
        $this->type = $type;

        return $this;
    }
}
