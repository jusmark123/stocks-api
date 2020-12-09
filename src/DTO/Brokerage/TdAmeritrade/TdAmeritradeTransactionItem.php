<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\TdAmeritrade;

/**
 * Class TdAmeritradeTransactionItem.
 */
class TdAmeritradeTransactionItem
{
    /**
     * @var int
     */
    private $accountId;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var float|null
     */
    private $price;

    /**
     * @var float|null
     */
    private $cost;

    /**
     * @var int|null
     */
    private $parentOrderKey;

    /**
     * @var string|null
     */
    private $parentChildIndicator;

    /**
     * @var string|null
     */
    private $instruction;

    /**
     * @var string|null
     */
    private $positionEffect;

    /**
     * @var TdAmeritradeInstrument
     */
    private $instrument;

    /**
     * @return int
     */
    public function getAccountId(): int
    {
        return $this->accountId;
    }

    /**
     * @param int $accountId
     *
     * @return TdAmeritradeTransactionItem
     */
    public function setAccountId(int $accountId): TdAmeritradeTransactionItem
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return TdAmeritradeTransactionItem
     */
    public function setAmount(float $amount): TdAmeritradeTransactionItem
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     *
     * @return TdAmeritradeTransactionItem
     */
    public function setPrice(?float $price): TdAmeritradeTransactionItem
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getCost(): ?float
    {
        return $this->cost;
    }

    /**
     * @param float|null $cost
     *
     * @return TdAmeritradeTransactionItem
     */
    public function setCost(?float $cost): TdAmeritradeTransactionItem
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getParentOrderKey(): ?int
    {
        return $this->parentOrderKey;
    }

    /**
     * @param int|null $parentOrderKey
     *
     * @return TdAmeritradeTransactionItem
     */
    public function setParentOrderKey(?int $parentOrderKey): TdAmeritradeTransactionItem
    {
        $this->parentOrderKey = $parentOrderKey;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getParentChildIndicator(): ?string
    {
        return $this->parentChildIndicator;
    }

    /**
     * @param string|null $parentChildIndicator
     *
     * @return TdAmeritradeTransactionItem
     */
    public function setParentChildIndicator(?string $parentChildIndicator): TdAmeritradeTransactionItem
    {
        $this->parentChildIndicator = $parentChildIndicator;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInstruction(): ?string
    {
        return $this->instruction;
    }

    /**
     * @param string|null $instruction
     *
     * @return TdAmeritradeTransactionItem
     */
    public function setInstruction(?string $instruction): TdAmeritradeTransactionItem
    {
        $this->instruction = $instruction;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPositionEffect(): ?string
    {
        return $this->positionEffect;
    }

    /**
     * @param string|null $positionEffect
     *
     * @return TdAmeritradeTransactionItem
     */
    public function setPositionEffect(?string $positionEffect): TdAmeritradeTransactionItem
    {
        $this->positionEffect = $positionEffect;

        return $this;
    }

    /**
     * @return TdAmeritradeInstrument
     */
    public function getInstrument(): TdAmeritradeInstrument
    {
        return $this->instrument;
    }

    /**
     * @param TdAmeritradeInstrument $instrument
     *
     * @return TdAmeritradeTransactionItem
     */
    public function setInstrument(TdAmeritradeInstrument $instrument): TdAmeritradeTransactionItem
    {
        $this->instrument = $instrument;

        return $this;
    }
}
