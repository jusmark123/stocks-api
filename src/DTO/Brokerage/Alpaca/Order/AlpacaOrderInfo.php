<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca\Order;

use App\DTO\Brokerage\OrderInfoInterface;
use App\Entity\Account;
use App\Entity\Order;
use App\Entity\Source;
use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\CreatedByTrait;
use App\Entity\Traits\ModifiedAtTrait;
use App\Entity\Traits\ModifiedByTrait;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

class AlpacaOrderInfo implements OrderInfoInterface
{
    use CreatedAtTrait;
    use CreatedByTrait;
    use ModifiedAtTrait;
    use ModifiedByTrait;

    /**
     * @var Account
     */
    private $account;

    /**
     * @var Order|null
     */
    private $order;

    /**
     * @var User|null
     */
    private $user;

    /**
     * @var Source
     */
    private $source;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $clientOrderId;

    /**
     * @var \DateTime|null
     */
    private $updatedAt;

    /**
     * @var \DateTime|null
     */
    private $submittedAt;

    /**
     * @var \DateTime|null
     */
    private $filledAt;

    /**
     * @var \DateTime|null
     */
    private $expiredAt;

    /**
     * @var \DateTime|null
     */
    private $cancelledAt;

    /**
     * @var \DateTime|null
     */
    private $failedAt;

    /**
     * @var \DateTime|null
     */
    private $replacedAt;

    /**
     * The order that this order was replaced by.
     *
     * @var AlpacaOrderInfo|null
     */
    private $replacedBy;

    /**
     * The order that this order replaces.
     *
     * @var AlpacaOrderInfo|null
     */
    private $replaces;

    /**
     * @var string
     */
    private $assetId;

    /**
     * @var string
     */
    private $symbol;

    /**
     * @var string
     */
    private $assetClass;

    /**
     * @var int
     */
    private $qty;

    /**
     * @var int
     */
    private $filledQty;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $side;

    /**
     * @var string
     */
    private $timeInForce;

    /**
     * @var float|null
     */
    private $limitPrice;

    /**
     * @var float|null
     */
    private $stopPrice;

    /**
     * @var float|null
     */
    private $filledAvgPrice;

    /**
     * @var string
     */
    private $status;

    /**
     * If true, eligible for execution outside regular trading hours.
     *
     * @var bool
     */
    private $extendedHours;

    /**
     * When querying non-simple order_class orders in a nested style, an array of Order entities associated with this order. Otherwise, null.
     *
     * @var ArrayCollection|AlpacaOrderInfo[]
     */
    private $legs;

    /**
     * The dollar value away from the high water mark for trailing stop orders.
     *
     * @var float|null
     */
    private $trailPrice;

    /**
     * The percent value away from the high water mark for trailing stop orders.
     *
     * @var float|null
     */
    private $trailPercent;

    /**
     * The highest (lowest) market price seen since the trailing stop order was submitted.
     *
     * @var float|null
     */
    private $hwm;

    /**
     * AlpacaOrderInfo Constructor.
     */
    public function __construct()
    {
        $this->legs = new ArrayCollection();
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     *
     * @return $this|OrderInfoInterface
     */
    public function setAccount(Account $account): OrderInfoInterface
    {
        $this->account = $account;

        return $this;
    }

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
     * @return $this|OrderInfoInterface
     */
    public function setOrder(Order $order): OrderInfoInterface
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return Source
     */
    public function getSource(): Source
    {
        return $this->source;
    }

    /**
     * @param Source $source
     *
     * @return AlpacaOrderInfo
     */
    public function setSource(Source $source): OrderInfoInterface
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     *
     * @return AlpacaOrderInfo
     */
    public function setUser(?User $user): AlpacaOrderInfo
    {
        $this->user = $user;

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
     * @return AlpacaOrderInfo
     */
    public function setId(string $id): AlpacaOrderInfo
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
     * @return AlpacaOrderInfo
     */
    public function setClientOrderId(string $clientOrderId): AlpacaOrderInfo
    {
        $this->clientOrderId = $clientOrderId;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime|null $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt = null): AlpacaOrderInfo
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getSubmittedAt(): ?\DateTime
    {
        return $this->submittedAt;
    }

    /**
     * @param \DateTime|null $submittedAt
     *
     * @return $this
     */
    public function setSubmittedAt(\DateTime $submittedAt = null): AlpacaOrderInfo
    {
        $this->submittedAt = $submittedAt;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getFilledAt(): ?\DateTime
    {
        return $this->filledAt;
    }

    /**
     * @param \DateTime|null $filledAt
     *
     * @return $this
     */
    public function setFilledAt(\DateTime $filledAt = null): AlpacaOrderInfo
    {
        $this->filledAt = $filledAt;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getExpiredAt(): ?\DateTime
    {
        return $this->expiredAt;
    }

    /**
     * @param \DateTime|null $expiredAt
     *
     * @return $this
     */
    public function setExpiredAt(\DateTime $expiredAt = null): AlpacaOrderInfo
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCancelledAt(): ?\DateTime
    {
        return $this->cancelledAt;
    }

    /**
     * @param \DateTime|null $cancelledAt
     *
     * @return $this
     */
    public function setCancelledAt(\DateTime $cancelledAt = null): AlpacaOrderInfo
    {
        $this->cancelledAt = $cancelledAt;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getFailedAt(): ?\DateTime
    {
        return $this->failedAt;
    }

    /**
     * @param \DateTime|null $failedAt
     *
     * @return $this
     */
    public function setFailedAt(\DateTime $failedAt = null): AlpacaOrderInfo
    {
        $this->failedAt = $failedAt;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getReplacedAt(): ?\DateTime
    {
        return $this->replacedAt;
    }

    /**
     * @param \DateTime|null $replacedAt
     *
     * @return $this
     */
    public function setReplacedAt(\DateTime $replacedAt = null): AlpacaOrderInfo
    {
        $this->replacedAt = $replacedAt;

        return $this;
    }

    /**
     * @return AlpacaOrderInfo|null
     */
    public function getReplacedBy(): ?AlpacaOrderInfo
    {
        return $this->replacedBy;
    }

    /**
     * @param AlpacaOrderInfo|null $replacedBy
     *
     * @return $this
     */
    public function setReplacedBy(?AlpacaOrderInfo $replacedBy): AlpacaOrderInfo
    {
        $this->replacedBy = $replacedBy;

        return $this;
    }

    /**
     * @return AlpacaOrderInfo|null
     */
    public function getReplaces(): ?AlpacaOrderInfo
    {
        return $this->replaces;
    }

    /**
     * @param AlpacaOrderInfo|null $replaces
     *
     * @return $this
     */
    public function setReplaces(?AlpacaOrderInfo $replaces): AlpacaOrderInfo
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
     * @return AlpacaOrderInfo
     */
    public function setAssetId(string $assetId): AlpacaOrderInfo
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
     * @return AlpacaOrderInfo
     */
    public function setSymbol(string $symbol): AlpacaOrderInfo
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
     * @return AlpacaOrderInfo
     */
    public function setAssetClass(string $assetClass): AlpacaOrderInfo
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
     * @param int $qty
     *
     * @return $this
     */
    public function setQty(string $qty = '0'): AlpacaOrderInfo
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
     * @param int $filledQty
     *
     * @return $this
     */
    public function setFilledQty(string $filledQty = '0'): AlpacaOrderInfo
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
     * @return AlpacaOrderInfo
     */
    public function setType(string $type): AlpacaOrderInfo
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
     * @return AlpacaOrderInfo
     */
    public function setSide(string $side): AlpacaOrderInfo
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
     * @return AlpacaOrderInfo
     */
    public function setTimeInForce(string $timeInForce): AlpacaOrderInfo
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
    public function setLimitPrice(?string $limitPrice): AlpacaOrderInfo
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
    public function setStopPrice(?string $stopPrice = null): AlpacaOrderInfo
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
    public function setFilledAvgPrice(?string $filledAvgPrice = null): AlpacaOrderInfo
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
     * @return AlpacaOrderInfo
     */
    public function setStatus(string $status): AlpacaOrderInfo
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
     * @param string $extendedHours
     *
     * @return AlpacaOrderInfo
     */
    public function setExtendedHours(bool $extendedHours): AlpacaOrderInfo
    {
        $this->extendedHours = $extendedHours;

        return $this;
    }

    /**
     * @return ArrayCollection|AlpacaOrderInfo[]
     */
    public function getLegs()
    {
        return $this->legs;
    }

    /**
     * @param ArrayCollection|AlpacaOrderInfo[] $legs
     *
     * @return AlpacaOrderInfo
     */
    public function setLegs($legs): AlpacaOrderInfo
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
    public function setTrailPrice(string $trailPrice = null): AlpacaOrderInfo
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
    public function setTrailPercent(string $trailPercent = null): AlpacaOrderInfo
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
    public function setHwm(?string $hwm): AlpacaOrderInfo
    {
        $this->hwm = (float) $hwm;

        return $this;
    }
}
