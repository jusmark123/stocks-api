<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * class Order.
 *
 * @ORM\Table(
 * 		name="`order`",
 * 		uniqueConstraints={
 * 			@ORM\UniqueConstraint(name="order_un_guid", columns={"guid"})
 * 		},
 * 		indexes={
 * 			@ORM\Index(name="order_ix_account_id", columns={"account_id"}),
 * 			@ORM\Index(name="order_ix_broker_id", columns={"brokerage_id"}),
 * 			@ORM\Index(name="order_ix_broker_order_id", columns={"broker_order_id"})
 * 		}
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\OrderRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class Order extends AbstractGuidEntity
{
    /**
     * @var string
     * @ORM\Column(name="broker_order_id", type="string", nullable=false)
     */
    private $brokerOrderId;

    /**
     * @var Brokerage
     * @ORM\ManyToOne(targetEntity="Brokerage", inversedBy="orders", fetch="LAZY")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="brokerage_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $brokerage;

    /**
     * @var Account
     *
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="orders")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $account;

    /**
     * @var Position
     * @ORM\ManyToOne(targetEntity="Position", inversedBy="orders", fetch="LAZY")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="position_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $position;

    /**
     * @var Source
     * @ORM\ManyToOne(targetEntity="Source", inversedBy="orders", fetch="LAZY")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="source_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $source;

    /**
     * @var User|null
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orders", fetch="LAZY")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $user;

    /**
     * @var float
     * @ORM\Column(name="amount_usd", type="float")
     */
    private $amountUsd;

    /**
     * @var OrderType
     * @ORM\ManyToOne(targetEntity="OrderType")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="order_type_id", referencedColumnName="id")
     * })
     */
    private $orderType;

    /**
     * @var int
     *
     * @ORM\Column(name="qty", type="integer", nullable=false, options={"default"=0})
     */
    private $filledQty;

    /**
     * @var float
     *
     * @ORM\Column(name="avg_cost", type="float", nullable=false, options={"default"=0.00})
     */
    private $avgCost;

    /**
     * @var float
     *
     * @ORM\Column(name="fees", type="float", nullable=false, options={"default"=0.00})
     */
    private $fees;

    /**
     * @var string
     *
     * @ORM\Column(name="side", type="string", length=5, nullable=false)
     */
    private $side;

    /**
     * @var OrderStatusType
     * @ORM\ManyToOne(targetEntity="OrderStatusType")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="order_status_type_id", referencedColumnName="id")
     * })
     */
    private $orderStatusType;

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
     * @return Order
     */
    public function setFilledQty(int $filledQty): Order
    {
        $this->filledQty = $filledQty;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getAvgCost(): float
    {
        return $this->avgCost;
    }

    /**
     * @param float $avgCost
     *
     * @return $this
     */
    public function setAvgCost(float $avgCost = 0.00): Order
    {
        $this->avgCost = $avgCost;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getFees(): float
    {
        return $this->fees;
    }

    /**
     * @param float $fees
     *
     * @return Order
     */
    public function setFees(float $fees = 0.00): Order
    {
        $this->fees = $fees;

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
     * @return Order
     */
    public function setSide(string $side): Order
    {
        $this->side = $side;

        return $this;
    }

    /**
     * @return string $brokerOrderId
     */
    public function getBrokerOrderId(): string
    {
        return $this->brokerOrderId;
    }

    public function setBrokerOrderId(string $brokerOrderId): Order
    {
        $this->brokerOrderId = $brokerOrderId;

        return $this;
    }

    /**
     * @return Brokerage $brokerage
     */
    public function getBrokerage(): Brokerage
    {
        return $this->brokerage;
    }

    public function setBrokerage(Brokerage $brokerage): Order
    {
        $this->brokerage = $brokerage;

        return $this;
    }

    /**
     * @return Position
     */
    public function getPosition(): Position
    {
        return $this->position;
    }

    /**
     * @param Position $position
     *
     * @return Order
     */
    public function setPosition(Position $position): Order
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Account $account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     *
     * @return Order
     */
    public function setAccount(Account $account): Order
    {
        $this->account = $account;

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
     * @return Order
     */
    public function setSource(Source $source): Order
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return User $user
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return Order
     */
    public function setUser(User $user): Order
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return OrderType
     */
    public function getOrderType(): OrderType
    {
        return $this->orderType = $orderType;
    }

    /**
     * @param OrderType $orderType
     *
     * @return Order
     */
    public function setOrderType(OrderType $orderType): Order
    {
        $this->orderType = $orderType;

        return $this;
    }

    /**
     * @return OrderStatusType
     */
    public function getOrderStatusType(): OrderStatusType
    {
        return $this->orderStatusType;
    }

    /**
     * @param OrderStatusType $orderStatusType
     *
     * @return Order
     */
    public function setOrderStatusType(OrderStatusType $orderStatusType): Order
    {
        $this->orderStatusType = $orderStatusType;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmountUsd(): float
    {
        return $this->amountUsd;
    }

    /**
     * @param float $amountUsd
     *
     * @return Order
     */
    public function setAmountUsd(float $amountUsd): Order
    {
        $this->amountUsd = $amountUsd;

        return $this;
    }
}
