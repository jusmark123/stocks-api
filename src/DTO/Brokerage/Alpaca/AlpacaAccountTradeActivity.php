<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca;

use App\DTO\Brokerage\AccountHistoryInterface;
use App\Entity\Traits\EntityGuidTrait;
use DateTime;
use Ramsey\Uuid\Uuid;

class AlpacaAccountTradeActivity implements AccountHistoryInterface
{
    use EntityGuidTrait;

    /**
     * @var string
     */
    private string $activityType;

    /**
     * @var float
     */
    private float $cumQty;

    /**
     * @var string
     */
    private string $id;

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
     * @var string
     */
    private string $symbol;

    /**
     * @var DateTime
     */
    private DateTime $transactionTime;

    /**
     * @var string
     */
    private string $orderId;

    /**
     * @var string
     */
    private string $type;

    /**
     * @return string
     */
    public function getActivityType(): string
    {
        return $this->activityType;
    }

    /**
     * @param string $activityType
     *
     * @return AlpacaAccountTradeActivity
     */
    public function setActivityType(string $activityType): AlpacaAccountTradeActivity
    {
        $this->activityType = $activityType;

        return $this;
    }

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
     * @return AlpacaAccountTradeActivity
     */
    public function setCumQty(float $cumQty): AlpacaAccountTradeActivity
    {
        $this->cumQty = $cumQty;

        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return AlpacaAccountTradeActivity
     */
    public function setId(string $id): AlpacaAccountTradeActivity
    {
        $this->id = $id;
        $this->setGuid(Uuid::fromString(explode('::', $id)[1]));

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
     * @return AlpacaAccountTradeActivity
     */
    public function setLeavesQty(int $leavesQty): AlpacaAccountTradeActivity
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
     * @return AlpacaAccountTradeActivity
     */
    public function setPrice(float $price): AlpacaAccountTradeActivity
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
     * @return AlpacaAccountTradeActivity
     */
    public function setQty(int $qty): AlpacaAccountTradeActivity
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
     * @return AlpacaAccountTradeActivity
     */
    public function setSide(string $side): AlpacaAccountTradeActivity
    {
        $this->side = $side;

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
     * @return AlpacaAccountTradeActivity
     */
    public function setSymbol(string $symbol): AlpacaAccountTradeActivity
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getTransactionTime(): DateTime
    {
        return $this->transactionTime;
    }

    /**
     * @param DateTime $transactionTime
     *
     * @return AlpacaAccountTradeActivity
     */
    public function setTransactionTime(DateTime $transactionTime): AlpacaAccountTradeActivity
    {
        $this->transactionTime = $transactionTime;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @param string $orderId
     *
     * @return AlpacaAccountTradeActivity
     */
    public function setOrderId(string $orderId): AlpacaAccountTradeActivity
    {
        $this->orderId = $orderId;

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
     * @return AlpacaAccountTradeActivity
     */
    public function setType(string $type): AlpacaAccountTradeActivity
    {
        $this->type = $type;

        return $this;
    }
}
