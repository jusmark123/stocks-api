<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca;

use App\DTO\Brokerage\AccountHistoryInterface;
use App\Entity\Traits\EntityGuidTrait;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class AlpacaAccountNonTradeActivity.
 */
class AlpacaAccountNonTradeActivity implements AccountHistoryInterface
{
    use EntityGuidTrait;

    /**
     * @var string
     */
    private string $activityType;

    /**
     * @var string
     */
    private string $id;

    /**
     * @var \DateTime
     */
    private \DateTime $date;

    /**
     * @var float
     */
    private float $netAmount = 0.00;

    /**
     * @var string|null
     */
    private ?string $symbol = null;

    /**
     * @var float|null
     */
    private ?float $qty = null;

    /**
     * @var float|null
     */
    private ?float $perShareAmount = null;

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
     * @return AlpacaAccountNonTradeActivity
     */
    public function setId(string $id): AlpacaAccountNonTradeActivity
    {
        $this->id = $id;
        $this->setGuid(Uuid::fromString(explode('::', $id)[1]));

        return $this;
    }

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
     * @return AlpacaAccountNonTradeActivity
     */
    public function setActivityType(string $activityType): AlpacaAccountNonTradeActivity
    {
        $this->activityType = $activityType;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     *
     * @return AlpacaAccountNonTradeActivity
     */
    public function setDate(\DateTime $date): AlpacaAccountNonTradeActivity
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return float
     */
    public function getNetAmount(): float
    {
        return $this->netAmount;
    }

    /**
     * @param float $netAmount
     *
     * @return AlpacaAccountNonTradeActivity
     */
    public function setNetAmount(float $netAmount): AlpacaAccountNonTradeActivity
    {
        $this->netAmount = $netAmount;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    /**
     * @param string|null $symbol
     *
     * @return AlpacaAccountNonTradeActivity
     */
    public function setSymbol(?string $symbol): AlpacaAccountNonTradeActivity
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getQty(): ?float
    {
        return $this->qty;
    }

    /**
     * @param float|null $qty
     *
     * @return AlpacaAccountNonTradeActivity
     */
    public function setQty(?float $qty): AlpacaAccountNonTradeActivity
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPerShareAmount(): ?float
    {
        return $this->perShareAmount;
    }

    /**
     * @param float|null $perShareAmount
     *
     * @return AlpacaAccountNonTradeActivity
     */
    public function setPerShareAmount(?float $perShareAmount): AlpacaAccountNonTradeActivity
    {
        $this->perShareAmount = $perShareAmount;

        return $this;
    }

    /**
     * @return UuidInterface
     */
    public function getGuid(): UuidInterface
    {
        return $this->guid;
    }

    /**
     * @param UuidInterface $guid
     *
     * @return AlpacaAccountNonTradeActivity
     */
    public function setGuid(UuidInterface $guid): AlpacaAccountNonTradeActivity
    {
        $this->guid = $guid;

        return $this;
    }
}
