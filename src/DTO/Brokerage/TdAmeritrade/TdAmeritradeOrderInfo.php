<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\TdAmeritrade;

use App\DTO\Brokerage\BrokerageOrderInterface;
use App\Entity\Account;
use App\Entity\Order;
use App\Entity\User;

class TdAmeritradeOrderInfo
{
    /**
     * @var Account
     */
    private $account;

    /**w
     * @var Order|null
     */
    private $order;

    /**
     * @var User|null
     */
    private $user;

    /**
     * @var string
     */
    private $orderId;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string|null
     */
    private $clearingHouseReferenceNumber;

    /**
     * @var string|null
     */
    private $subAccount;

    /**
     * @var int
     */
    private $sma;

    /**
     * @var float|null
     */
    private $requirementRelocationAmount;

    /**
     * @var float|null
     */
    private $dayTradeBuyingPowerEffect;

    /**
     * @var float
     */
    private $netAmount;

    /**
     * @var \DateTime|null
     */
    private $transactionDate;

    /**
     * @var \DateTime
     */
    private $orderDate;

    /**
     * @var string|null
     */
    private $transactionSubType;

    /**
     * @var string
     */
    private $transactionId;

    /**
     * @var bool
     */
    private $cashBalanceEffectFlag;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $achStatus;

    /**
     * @var float|null
     */
    private $accruedInterest;

    /**
     * @var float|null
     */
    private $fees;

    /**
     * @var TdAmeritradeTransactionItem
     */
    private $transactionItem;

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
     * @return $this|BrokerageOrderInterface
     */
    public function setOrder(?Order $order): BrokerageOrderInterface
    {
        $this->order = $order;

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
     * @return TdAmeritradeOrderInfo
     */
    public function setUser(?User $user): TdAmeritradeOrderInfo
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->orderId;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->orderDate;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->achStatus;
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
     * @return TdAmeritradeOrderInfo
     */
    public function setOrderId(string $orderId): TdAmeritradeOrderInfo
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
     * @return TdAmeritradeOrderInfo
     */
    public function setType(string $type): TdAmeritradeOrderInfo
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getClearingHouseReferenceNumber(): ?string
    {
        return $this->clearingHouseReferenceNumber;
    }

    /**
     * @param string|null $clearingHouseReferenceNumber
     *
     * @return TdAmeritradeOrderInfo
     */
    public function setClearingHouseReferenceNumber(?string $clearingHouseReferenceNumber): TdAmeritradeOrderInfo
    {
        $this->clearingHouseReferenceNumber = $clearingHouseReferenceNumber;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubAccount(): ?string
    {
        return $this->subAccount;
    }

    /**
     * @param string|null $subAccount
     *
     * @return TdAmeritradeOrderInfo
     */
    public function setSubAccount(?string $subAccount): TdAmeritradeOrderInfo
    {
        $this->subAccount = $subAccount;

        return $this;
    }

    /**
     * @return int
     */
    public function getSma(): int
    {
        return $this->sma;
    }

    /**
     * @param int $sma
     *
     * @return TdAmeritradeOrderInfo
     */
    public function setSma(int $sma): TdAmeritradeOrderInfo
    {
        $this->sma = $sma;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getRequirementRelocationAmount(): ?float
    {
        return $this->requirementRelocationAmount;
    }

    /**
     * @param float|null $requirementRelocationAmount
     *
     * @return TdAmeritradeOrderInfo
     */
    public function setRequirementRelocationAmount(?float $requirementRelocationAmount): TdAmeritradeOrderInfo
    {
        $this->requirementRelocationAmount = $requirementRelocationAmount;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getDayTradeBuyingPowerEffect(): ?float
    {
        return $this->dayTradeBuyingPowerEffect;
    }

    /**
     * @param float|null $dayTradeBuyingPowerEffect
     *
     * @return TdAmeritradeOrderInfo
     */
    public function setDayTradeBuyingPowerEffect(?float $dayTradeBuyingPowerEffect): TdAmeritradeOrderInfo
    {
        $this->dayTradeBuyingPowerEffect = $dayTradeBuyingPowerEffect;

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
     * @return TdAmeritradeOrderInfo
     */
    public function setNetAmount(float $netAmount): TdAmeritradeOrderInfo
    {
        $this->netAmount = $netAmount;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getTransactionDate(): ?\DateTime
    {
        return $this->transactionDate;
    }

    /**
     * @param \DateTime|null $transactionDate
     *
     * @return TdAmeritradeOrderInfo
     */
    public function setTransactionDate(?\DateTime $transactionDate): TdAmeritradeOrderInfo
    {
        $this->transactionDate = $transactionDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getOrderDate(): \DateTime
    {
        return $this->orderDate;
    }

    /**
     * @param \DateTime $orderDate
     *
     * @return TdAmeritradeOrderInfo
     */
    public function setOrderDate(\DateTime $orderDate): TdAmeritradeOrderInfo
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTransactionSubType(): ?string
    {
        return $this->transactionSubType;
    }

    /**
     * @param string|null $transactionSubType
     *
     * @return TdAmeritradeOrderInfo
     */
    public function setTransactionSubType(?string $transactionSubType): TdAmeritradeOrderInfo
    {
        $this->transactionSubType = $transactionSubType;

        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    /**
     * @param string $transactionId
     *
     * @return TdAmeritradeOrderInfo
     */
    public function setTransactionId(string $transactionId): TdAmeritradeOrderInfo
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * @return bool
     */
    public function isCashBalanceEffectFlag(): bool
    {
        return $this->cashBalanceEffectFlag;
    }

    /**
     * @param bool $cashBalanceEffectFlag
     *
     * @return TdAmeritradeOrderInfo
     */
    public function setCashBalanceEffectFlag(bool $cashBalanceEffectFlag): TdAmeritradeOrderInfo
    {
        $this->cashBalanceEffectFlag = $cashBalanceEffectFlag;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return TdAmeritradeOrderInfo
     */
    public function setDescription(string $description): TdAmeritradeOrderInfo
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getAchStatus(): string
    {
        return $this->achStatus;
    }

    /**
     * @param string $achStatus
     *
     * @return TdAmeritradeOrderInfo
     */
    public function setAchStatus(string $achStatus): TdAmeritradeOrderInfo
    {
        $this->achStatus = $achStatus;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getAccruedInterest(): ?float
    {
        return $this->accruedInterest;
    }

    /**
     * @param float|null $accruedInterest
     *
     * @return TdAmeritradeOrderInfo
     */
    public function setAccruedInterest(?float $accruedInterest): TdAmeritradeOrderInfo
    {
        $this->accruedInterest = $accruedInterest;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getFees(): ?float
    {
        return $this->fees;
    }

    /**
     * @param float|null $fees
     *
     * @return TdAmeritradeOrderInfo
     */
    public function setFees(?float $fees): TdAmeritradeOrderInfo
    {
        $this->fees = $fees;

        return $this;
    }

    /**
     * @return TdAmeritradeTransactionItem
     */
    public function getTransactionItem(): TdAmeritradeTransactionItem
    {
        return $this->transactionItem;
    }

    /**
     * @param TdAmeritradeTransactionItem $transactionItem
     *
     * @return TdAmeritradeOrderInfo
     */
    public function setTransactionItem(TdAmeritradeTransactionItem $transactionItem): TdAmeritradeOrderInfo
    {
        $this->transactionItem = $transactionItem;

        return $this;
    }
}
