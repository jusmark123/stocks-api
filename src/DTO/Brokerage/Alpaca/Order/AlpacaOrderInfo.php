<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca\Order;

use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Account;
use App\Entity\Order;
use Doctrine\Common\Collections\ArrayCollection;

class AlpacaOrderInfo implements OrderInfoInterface
{
    /**
     * @var Account
     */
    private $account;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $clientOrderId;

    /**
     * @var \DateTime
     */
    private $createdAt;

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
     * @var \DateTime
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
    private $asset_id;

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
     * @var float
     */
    private $trailPrice;

    /**
     * The percent value away from the high water mark for trailing stop orders.
     *
     * @var float
     */
    private $trailPercent;

    /**
     * The highest (lowest) market price seen since the trailing stop order was submitted.
     *
     * @var float
     */
    private $hwn;

    /**
     * AlpacaOrderInfo Constructor.
     */
    public function __construct()
    {
        $this->legs = new ArrayCollection([$elements]);
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
     * @return AlpacaOrderInfo
     */
    public function setAccount(Account $account): AlpacaOrderInfo
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
     * @return AlpacaOrderInfo
     */
    public function setOrder(Order $order): AlpacaOrderInfo
    {
        $this->order = $order;

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
     * @return DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param  \DateTime|null
     *
     * @return AlpacaOrderInfo
     */
    public function setCreatedAt(\DateTime $createdAt): AlpacaOrderInfo
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAtt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param  \DateTime|null
     *
     * @return AlpacaOrderInfo
     */
    public function setUpdatedAt(\DateTime $updatedAt): AlpacaOrderInfo
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
     * @param DateTime $submittedAt
     *
     * @return AlpacaOrderInfo
     */
    public function setSubmittedAt(\DateTime $submittedAt): AlpacaOrderInfo
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
     * @param DateTime $filledAt
     *
     * @return AlpacaOrderInfo
     */
    public function setFilledAt(\DateTime $filledAt): AlpacaOrderInfo
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
     * @param DateTime $expiredAt
     *
     * @return AlpacaOrderInfo
     */
    public function setExpiredAt(\DateTime $expiredAt): AlpacaOrderInfo
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
     * @param DateTime $cancelledAt
     *
     * @return AlpacaOrderInfo
     */
    public function setCancelledAt(\DateTime $cancelledAt): AlpacaOrderInfo
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
     * @param DateTime $updatedAt
     *
     * @return AlpacaOrderInfo
     */
    public function setFailedAt(\DateTime $updatedAt): AlpacaOrderInfo
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
     * @param DateTime $replaceAt
     *
     * @return AlpacaOrderInfo
     */
    public function setReplacedAt(\DateTime $replaceAt): AlpacaOrderInfo
    {
        $this->replacedAt = $replacedAt;

        return $this;
    }

    /**
     * @return AlpacaOrderInfo|null
     */
    public function getReplacedBy(): ?AlpacaAccountInfo
    {
        return $this->replacedBy;
    }

    /**
     * @param AlpacaOrderInfo $replacedBy
     *
     * @return AlpacaOrderInfo
     */
    public function setReplacedBy(AlpacaOrderInfo $replacedBy): AlpacaOrderInfo
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
     * @param AlpacaOrderInfo $replaces
     *
     * @return AlpacaOrderInfo
     */
    public function setReplaces(\AlpacaOrderInfo $replaces): AlpacaOrderInfo
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
     * @return int
     */
    public function getQty(): int
    {
        return $this->qty;
    }

    /**
     * @param string $qty
     *
     * @return AlpacaOrderInfo
     */
    public function setQty(string $qty): AlpacaOrderInfo
    {
        $this->qty = $qty;

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
     * @param string $filledQty
     *
     * @return AlpacaOrderInfo
     */
    public function setFilledQty(string $filledQty): AlpacaOrderInfo
    {
        $this->filledQty = $filledQty;

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
     * @param string $limitPrice
     *
     * @return AlpacaOrderInfo
     */
    public function setLimitPrice(string $limitPrice): AlpacaOrderInfo
    {
        $this->limitPrice = (float) $limitPrice;

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
     * @param string $stopPrice
     *
     * @return AlpacaOrderInfo
     */
    public function setStopPrice(string $stopPrice): AlpacaOrderInfo
    {
        $this->stopPrice = (float) $stopPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getFilledAvgPrice(): float
    {
        return $this->filledAvgPrice;
    }

    /**
     * @param string $filledAvgPrice
     *
     * @return AlpacaOrderInfo
     */
    public function setFilledAvgPrice(string $filledAvgPrice): AlpacaOrderInfo
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
    public function setExtendedHours(string $extendedHours): AlpacaOrderInfo
    {
        $this->extendedHours = $extendedHours;

        return $this;
    }

    /**
     * @return [type]
     */
    public function getLegs()
    {
        return $this->legs;
    }

    /**
     * @param [type] $legs
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
     * @param string $trailPrice
     *
     * @return AlpacaOrderInfo
     */
    public function setTrailPrice(string $trailPrice): AlpacaOrderInfo
    {
        $this->trailPrice = (float) $trailPrice;

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
     * @param string $trailPercent
     *
     * @return AlpacaOrderInfo
     */
    public function setTrailPercent(string $trailPercent): AlpacaOrderInfo
    {
        $this->trailPercent = (float) $trailPercent;

        return $this;
    }

    /**
     * @return float
     */
    public function getHwm(): float
    {
        return $this->hwm;
    }

    /**
     * @param string $hwm
     *
     * @return AlpacaOrderInfo
     */
    public function setHwm(string $hwm): AlpacaOrderInfo
    {
        $this->hwm = (float) $hwm;

        return $this;
    }
}
