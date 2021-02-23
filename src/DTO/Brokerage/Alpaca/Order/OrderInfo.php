<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca\Order;

use App\DTO\Brokerage\BrokerageOrderInterface;
use App\Entity\Account;
use App\Entity\Order;
use App\Entity\Source;
use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\ModifiedAtTrait;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

class OrderInfo implements BrokerageOrderInterface
{
    use CreatedAtTrait;
    use ModifiedAtTrait;

    /**
     * @var Account
     */
    private Account $account;

    /**
     * @var Order|null
     */
    private ?Order $order;

    /**
     * @var User|null
     */
    private ?User $user;

    /**
     * @var Source
     */
    private Source $source;

    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $clientOrderId;

    /**
     * @var \DateTime|null
     */
    private ?\DateTime $updatedAt;

    /**
     * @var \DateTime|null
     */
    private ?\DateTime $submittedAt;

    /**
     * @var \DateTime|null
     */
    private ?\DateTime $filledAt;

    /**
     * @var \DateTime|null
     */
    private ?\DateTime $expiredAt;

    /**
     * @var \DateTime|null
     */
    private ?\DateTime $cancelledAt;

    /**
     * @var \DateTime|null
     */
    private ?\DateTime $failedAt;

    /**
     * @var \DateTime|null
     */
    private ?\DateTime $replacedAt;

    /**
     * The order that this order was replaced by.
     *
     * @var OrderInfo|null
     */
    private ?OrderInfo $replacedBy;

    /**
     * The order that this order replaces.
     *
     * @var OrderInfo|null
     */
    private ?OrderInfo $replaces;

    /**
     * @var string
     */
    private string $assetId;

    /**
     * @var string
     */
    private string $symbol;

    /**
     * @var string
     */
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
     * When querying non-simple order_class orders in a nested style, an array of OrderInfo entities associated with this order. Otherwise, null.
     *
     * @var ArrayCollection|OrderInfo[]
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
     * OrderInfo Constructor.
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
     * @return $this|BrokerageOrderInterface
     */
    public function setAccount(Account $account): BrokerageOrderInterface
    {
        $this->account = $account;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): BrokerageOrderInterface
    {
        $this->order = $order;

        return $order;
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
     * @return OrderInfo
     */
    public function setSource(Source $source): BrokerageOrderInterface
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
     * @return OrderInfo
     */
    public function setUser(?User $user): OrderInfo
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
     * @return OrderInfo
     */
    public function setId(string $id): OrderInfo
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
     * @return OrderInfo
     */
    public function setClientOrderId(string $clientOrderId): OrderInfo
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
    public function setUpdatedAt(\DateTime $updatedAt = null): OrderInfo
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
    public function setSubmittedAt(\DateTime $submittedAt = null): OrderInfo
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
    public function setFilledAt(\DateTime $filledAt = null): OrderInfo
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
    public function setExpiredAt(\DateTime $expiredAt = null): OrderInfo
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
    public function setCancelledAt(\DateTime $cancelledAt = null): OrderInfo
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
    public function setFailedAt(\DateTime $failedAt = null): OrderInfo
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
    public function setReplacedAt(\DateTime $replacedAt = null): OrderInfo
    {
        $this->replacedAt = $replacedAt;

        return $this;
    }

    /**
     * @return OrderInfo|null
     */
    public function getReplacedBy(): ?OrderInfo
    {
        return $this->replacedBy;
    }

    /**
     * @param OrderInfo|null $replacedBy
     *
     * @return $this
     */
    public function setReplacedBy(?OrderInfo $replacedBy): OrderInfo
    {
        $this->replacedBy = $replacedBy;

        return $this;
    }

    /**
     * @return OrderInfo|null
     */
    public function getReplaces(): ?OrderInfo
    {
        return $this->replaces;
    }

    /**
     * @param OrderInfo|null $replaces
     *
     * @return $this
     */
    public function setReplaces(?OrderInfo $replaces): OrderInfo
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
     * @return OrderInfo
     */
    public function setAssetId(string $assetId): OrderInfo
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
     * @return OrderInfo
     */
    public function setSymbol(string $symbol): OrderInfo
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
     * @return OrderInfo
     */
    public function setAssetClass(string $assetClass): OrderInfo
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
    public function setQty(string $qty = '0'): OrderInfo
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
    public function setFilledQty(string $filledQty = '0'): OrderInfo
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
     * @return OrderInfo
     */
    public function setType(string $type): OrderInfo
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
     * @return OrderInfo
     */
    public function setSide(string $side): OrderInfo
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
     * @return OrderInfo
     */
    public function setTimeInForce(string $timeInForce): OrderInfo
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
    public function setLimitPrice(?string $limitPrice): OrderInfo
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
    public function setStopPrice(?string $stopPrice = null): OrderInfo
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
    public function setFilledAvgPrice(?string $filledAvgPrice = null): OrderInfo
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
     * @return OrderInfo
     */
    public function setStatus(string $status): OrderInfo
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
     * @return OrderInfo
     */
    public function setExtendedHours(bool $extendedHours): OrderInfo
    {
        $this->extendedHours = $extendedHours;

        return $this;
    }

    /**
     * @return ArrayCollection|OrderInfo[]
     */
    public function getLegs()
    {
        return $this->legs;
    }

    /**
     * @param ArrayCollection|OrderInfo[] $legs
     *
     * @return OrderInfo
     */
    public function setLegs($legs): OrderInfo
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
    public function setTrailPrice(string $trailPrice = null): OrderInfo
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
    public function setTrailPercent(string $trailPercent = null): OrderInfo
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
    public function setHwm(?string $hwm): OrderInfo
    {
        $this->hwm = (float) $hwm;

        return $this;
    }
}
