<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca\Order;

use App\DTO\Brokerage\BrokerageOrderInterface;
use App\Entity\Source;
use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\ModifiedAtTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

class AlpacaOrder implements BrokerageOrderInterface
{
    use CreatedAtTrait;
    use ModifiedAtTrait;

    /** @var string */
    private string $clientOrderId;

    /** @var string */
    private string $id;

    /** @var Source */
    private Source $source;

    /** @var DateTime|null */
    private ?DateTime $updatedAt;

    /** @var DateTime|null */
    private ?DateTime $submittedAt;

    /** @var DateTime|null */
    private ?DateTime $filledAt;

    /** @var DateTime|null */
    private ?DateTime $expiredAt;

    /** @var DateTime|null */
    private ?DateTime $cancelledAt;

    /** @var DateTime|null */
    private ?DateTime $failedAt;

    /** @var DateTime|null */
    private ?DateTime $replacedAt;

    /**
     * The order that this order was replaced by.
     *
     * @var AlpacaOrder|null
     */
    private ?AlpacaOrder $replacedBy;

    /** @var AlpacaOrder|null The order that this order replaces. */
    private ?AlpacaOrder $replaces;

    /** @var string */
    private string $assetId;

    /** @var string */
    private string $symbol;

    /** @var string */
    private string $assetClass;

    /**
     * @var int
     */
    private int $qty;

    /**
     * @var int
     */
    private int $filledQty;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var string
     */
    private string $side;

    /**
     * @var string
     */
    private string $timeInForce;

    /**
     * @var float|null
     */
    private ?float $limitPrice;

    /**
     * @var float|null
     */
    private ?float $stopPrice;

    /**
     * @var float|null
     */
    private ?float $filledAvgPrice;

    /**
     * @var string
     */
    private string $status;

    /**
     * If true, eligible for execution outside regular trading hours.
     *
     * @var bool
     */
    private bool $extendedHours;

    /**
     * When querying non-simple order_class orders in a nested style, an array of AlpacaOrder entities associated with this order. Otherwise, null.
     *
     * @var ArrayCollection|AlpacaOrder[]
     */
    private $legs;

    /**
     * The dollar value away from the high water mark for trailing stop orders.
     *
     * @var float|null
     */
    private ?float $trailPrice;

    /**
     * The percent value away from the high water mark for trailing stop orders.
     *
     * @var float|null
     */
    private ?float $trailPercent;

    /**
     * The highest (lowest) market price seen since the trailing stop order was submitted.
     *
     * @var float|null
     */
    private ?float $hwm;

    /**
     * AlpacaOrder Constructor.
     */
    public function __construct()
    {
        $this->legs = new ArrayCollection();
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
     * @return AlpacaOrder
     */
    public function setId(string $id): AlpacaOrder
    {
        $this->id = $id;

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
     * @return AlpacaOrder
     */
    public function setClientOrderId(string $clientOrderId): AlpacaOrder
    {
        $this->clientOrderId = $clientOrderId;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime|null $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(DateTime $updatedAt = null): AlpacaOrder
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getSubmittedAt(): ?DateTime
    {
        return $this->submittedAt;
    }

    /**
     * @param DateTime|null $submittedAt
     *
     * @return $this
     */
    public function setSubmittedAt(DateTime $submittedAt = null): AlpacaOrder
    {
        $this->submittedAt = $submittedAt;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getFilledAt(): ?DateTime
    {
        return $this->filledAt;
    }

    /**
     * @param DateTime|null $filledAt
     *
     * @return $this
     */
    public function setFilledAt(DateTime $filledAt = null): AlpacaOrder
    {
        $this->filledAt = $filledAt;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getExpiredAt(): ?DateTime
    {
        return $this->expiredAt;
    }

    /**
     * @param DateTime|null $expiredAt
     *
     * @return $this
     */
    public function setExpiredAt(DateTime $expiredAt = null): AlpacaOrder
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getCancelledAt(): ?DateTime
    {
        return $this->cancelledAt;
    }

    /**
     * @param DateTime|null $cancelledAt
     *
     * @return $this
     */
    public function setCancelledAt(DateTime $cancelledAt = null): AlpacaOrder
    {
        $this->cancelledAt = $cancelledAt;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getFailedAt(): ?DateTime
    {
        return $this->failedAt;
    }

    /**
     * @param DateTime|null $failedAt
     *
     * @return $this
     */
    public function setFailedAt(DateTime $failedAt = null): AlpacaOrder
    {
        $this->failedAt = $failedAt;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getReplacedAt(): ?DateTime
    {
        return $this->replacedAt;
    }

    /**
     * @param DateTime|null $replacedAt
     *
     * @return $this
     */
    public function setReplacedAt(DateTime $replacedAt = null): AlpacaOrder
    {
        $this->replacedAt = $replacedAt;

        return $this;
    }

    /**
     * @return AlpacaOrder|null
     */
    public function getReplacedBy(): ?AlpacaOrder
    {
        return $this->replacedBy;
    }

    /**
     * @param AlpacaOrder|null $replacedBy
     *
     * @return $this
     */
    public function setReplacedBy(?AlpacaOrder $replacedBy): AlpacaOrder
    {
        $this->replacedBy = $replacedBy;

        return $this;
    }

    /**
     * @return AlpacaOrder|null
     */
    public function getReplaces(): ?AlpacaOrder
    {
        return $this->replaces;
    }

    /**
     * @param AlpacaOrder|null $replaces
     *
     * @return $this
     */
    public function setReplaces(?AlpacaOrder $replaces): AlpacaOrder
    {
        $this->replaces = $replaces;

        return $this;
    }

    /**
     * @return string
     */
    public function getAssetId(): string
    {
        return $this->assetId;
    }

    /**
     * @param string $assetId
     *
     * @return AlpacaOrder
     */
    public function setAssetId(string $assetId): AlpacaOrder
    {
        $this->assetId = $assetId;

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
     * @return AlpacaOrder
     */
    public function setSymbol(string $symbol): AlpacaOrder
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * @return string
     */
    public function getAssetClass(): string
    {
        return $this->assetClass;
    }

    /**
     * @param string $assetClass
     *
     * @return AlpacaOrder
     */
    public function setAssetClass(string $assetClass): AlpacaOrder
    {
        $this->assetClass = $assetClass;

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
     * @param string $qty
     *
     * @return $this
     */
    public function setQty(string $qty = '0'): AlpacaOrder
    {
        $this->qty = (int) $qty;

        return $this;
    }

    /**
     * @return int
     */
    public function getFilledQty(): int
    {
        return $this->filledQty;
    }

    /**
     * @param string|int $filledQty
     *
     * @return $this
     */
    public function setFilledQty(string $filledQty): AlpacaOrder
    {
        $this->filledQty = (int) $filledQty;

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
     * @return AlpacaOrder
     */
    public function setType(string $type): AlpacaOrder
    {
        $this->type = $type;

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
     * @return AlpacaOrder
     */
    public function setSide(string $side): AlpacaOrder
    {
        $this->side = $side;

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
     * @return AlpacaOrder
     */
    public function setTimeInForce(string $timeInForce): AlpacaOrder
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
     * @param string|null $limitPrice
     *
     * @return $this
     */
    public function setLimitPrice(?string $limitPrice): AlpacaOrder
    {
        $this->limitPrice = (float) $limitPrice;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getStopPrice(): ?float
    {
        return $this->stopPrice;
    }

    /**
     * @param string|null $stopPrice
     *
     * @return $this
     */
    public function setStopPrice(?string $stopPrice = null): AlpacaOrder
    {
        $this->stopPrice = (float) $stopPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getFilledAvgPrice(): ?float
    {
        return $this->filledAvgPrice;
    }

    /**
     * @param string|null $filledAvgPrice
     *
     * @return $this
     */
    public function setFilledAvgPrice(?string $filledAvgPrice = null): AlpacaOrder
    {
        $this->filledAvgPrice = (float) $filledAvgPrice;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return AlpacaOrder
     */
    public function setStatus(string $status): AlpacaOrder
    {
        $this->status = $status;

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
     * @return AlpacaOrder
     */
    public function setExtendedHours(bool $extendedHours): AlpacaOrder
    {
        $this->extendedHours = $extendedHours;

        return $this;
    }

    /**
     * @return ArrayCollection|AlpacaOrder[]
     */
    public function getLegs()
    {
        return $this->legs;
    }

    /**
     * @param ArrayCollection|AlpacaOrder[] $legs
     *
     * @return AlpacaOrder
     */
    public function setLegs($legs): AlpacaOrder
    {
        $this->legs = $legs;

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
     * @param string|null $trailPrice
     *
     * @return $this
     */
    public function setTrailPrice(string $trailPrice = null): AlpacaOrder
    {
        $this->trailPrice = (float) $trailPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getTrailPercent(): ?float
    {
        return $this->trailPercent;
    }

    /**
     * @param string|null $trailPercent
     *
     * @return $this
     */
    public function setTrailPercent(string $trailPercent = null): AlpacaOrder
    {
        $this->trailPercent = (float) $trailPercent;

        return $this;
    }

    /**
     * @return float
     */
    public function getHwm(): ?float
    {
        return $this->hwm;
    }

    /**
     * @param string|null $hwm
     *
     * @return $this
     */
    public function setHwm(?string $hwm): AlpacaOrder
    {
        $this->hwm = (float) $hwm;

        return $this;
    }
}
