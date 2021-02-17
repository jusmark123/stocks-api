<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca;

use App\Entity\AbstractGuidEntity;
use App\Entity\Order;
use App\Entity\Ticker;

class AccountActivity extends AbstractGuidEntity
{
    /**
     * @var float
     */
    private $cumQty;

    /**
     * @var int
     */
    private $leavesQty;

    /**
     * @var float
     */
    private $price;

    /**
     * @var int
     */
    private $qty;

    /**
     * @var string
     */
    private $side;

    /**
     * @var Ticker
     */
    private $ticker;

    /**
     * @var Order|null
     */
    private $order;

    /**
     * @var string
     */
    private $type;

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
     * @return AccountActivity
     */
    public function setCumQty(float $cumQty): AccountActivity
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
     * @return AccountActivity
     */
    public function setLeavesQty(int $leavesQty): AccountActivity
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
     * @return AccountActivity
     */
    public function setPrice(float $price): AccountActivity
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
     * @return AccountActivity
     */
    public function setQty(int $qty): AccountActivity
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
     * @return AccountActivity
     */
    public function setSide(string $side): AccountActivity
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
     * @return AccountActivity
     */
    public function setTicker(Ticker $ticker): AccountActivity
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
     * @return AccountActivity
     */
    public function setOrder(?Order $order): AccountActivity
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
     * @return AccountActivity
     */
    public function setType(string $type): AccountActivity
    {
        $this->type = $type;

        return $this;
    }
}
