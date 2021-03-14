<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use App\DTO\Brokerage\Alpaca\Order\AlpacaOrder;
use App\DTO\Brokerage\BrokerageOrderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * class AlpacaOrder.
 *
 * @ORM\Table(
 *        name="`order`",
 *        uniqueConstraints={
 * 			@ORM\UniqueConstraint(name="order_un_guid", columns={"guid"}),
 *        },
 *        indexes={
 * 			@ORM\Index(name="order_ix_account_id", columns={"account_id"}),
 *        }
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\OrderRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class Order extends AbstractGuidEntity
{
    /**
     * @var Account
     *
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="orders")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=false)
     */
    private Account $account;

    /**
     * @var float
     *
     * @ORM\Column(name="amount_usd", type="float", nullable=false, options={"default"=0.00})
     */
    private float $amountUsd = 0.00;

    /**
     * @var float
     *
     * @ORM\Column(name="filled_average_price", type="float", nullable=false, options={"default"=0.00})
     */
    private float $filledAveragePrice;

    /**
     * @var BrokerageOrderInterface|AlpacaOrder
     */
    private BrokerageOrderInterface $orderSummary;

    /**
     * @var ArrayCollection|OrderLog[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="OrderLog", mappedBy="order", fetch="LAZY", cascade={"persist", "remove"})
     */
    private $orderLogs;

    /**
     * @var OrderStatusType
     *
     * @ORM\ManyToOne(targetEntity="OrderStatusType")
     * @ORM\JoinColumn(name="order_status_type_id", referencedColumnName="id")
     */
    private OrderStatusType $orderStatus;

    /**
     * @var OrderType
     *
     * @ORM\ManyToOne(targetEntity="OrderType")
     * @ORM\JoinColumn(name="order_type_id", referencedColumnName="id")
     */
    private OrderType $orderType;

    /**
     * @var string
     *
     * @ORM\Column(name="side", type="enumSideType", length=20, nullable=false)
     */
    private string $side;

    /**
     * @var Source
     *
     * @ORM\ManyToOne(targetEntity="Source", inversedBy="orders", fetch="LAZY", cascade={"remove"})
     * @ORM\JoinColumn(name="source_id", referencedColumnName="id", nullable=false)
     */
    private Source $source;

    /**
     * @var Ticker
     *
     * @ORM\ManyToOne(targetEntity="Ticker", inversedBy="orders", fetch="LAZY")
     * @ORM\JoinColumn(name="ticker_id", referencedColumnName="id", nullable=false)
     */
    private Ticker $ticker;

    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="float", nullable=false, options={"default"=0.00})
     */
    private float $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="quantity_filled", type="float", nullable=false, options={"default"=0.00})
     */
    private float $quantityFilled;

    /**
     * @var Position
     *
     * @ORM\ManyToOne(targetEntity="Position", inversedBy="orders", fetch="LAZY", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="position_id", referencedColumnName="id", nullable=false)
     */
    private Position $position;

    /**
     * @var PositionLog
     *
     * @ORM\OneToOne(targetEntity="PositionLog", mappedBy="order", fetch="LAZY", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="position_log_id", referencedColumnName="id", nullable=false)
     */
    private PositionLog $positionLog;

    /**
     * AlpacaOrder constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->orderLogs = new ArrayCollection();
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

    /**
     * @return float
     */
    public function getFilledAveragePrice(): float
    {
        return $this->filledAveragePrice;
    }

    /**
     * @param float $filledAveragePrice
     *
     * @return Order
     */
    public function setFilledAveragePrice(float $filledAveragePrice): Order
    {
        $this->filledAveragePrice = $filledAveragePrice;

        return $this;
    }

    /**
     * @return BrokerageOrderInterface
     */
    public function getOrderSummary(): BrokerageOrderInterface
    {
        return $this->orderSummary;
    }

    /**
     * @param BrokerageOrderInterface $orderSummary
     *
     * @return Order
     */
    public function setOrderSummary(BrokerageOrderInterface $orderSummary): Order
    {
        $this->orderSummary = $orderSummary;

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
     * @return OrderLog[]|ArrayCollection|PersistentCollection
     */
    public function getOrderLogs()
    {
        return $this->orderLogs;
    }

    /**
     * @param OrderLog[]|ArrayCollection|PersistentCollection $orderLogs
     *
     * @return Order
     */
    public function setOrderLogs($orderLogs): Order
    {
        $this->orderLogs = $orderLogs;

        return $this;
    }

    /**
     * @return OrderType
     */
    public function getOrderType(): OrderType
    {
        return $this->orderType;
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
    public function getOrderStatus(): OrderStatusType
    {
        return $this->orderStatus;
    }

    /**
     * @param OrderStatusType $orderStatus
     *
     * @return Order
     */
    public function setOrderStatus(OrderStatusType $orderStatus): Order
    {
        $this->orderStatus = $orderStatus;

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
     * @return PositionLog
     */
    public function getPositionLog(): PositionLog
    {
        return $this->positionLog;
    }

    /**
     * @param PositionLog $positionLog
     *
     * @return Order
     */
    public function setPositionLog(PositionLog $positionLog): Order
    {
        $this->positionLog = $positionLog;

        return $this;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     *
     * @return Order
     */
    public function setQuantity(float $quantity): Order
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return float
     */
    public function getQuantityFilled(): float
    {
        return $this->quantityFilled;
    }

    /**
     * @param float $quantityFilled
     *
     * @return Order
     */
    public function setQuantityFilled(float $quantityFilled): Order
    {
        $this->quantityFilled = $quantityFilled;

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
     * @return Ticker
     */
    public function getTicker(): Ticker
    {
        return $this->ticker;
    }

    /**
     * @param Ticker $ticker
     *
     * @return Order
     */
    public function setTicker(Ticker $ticker): Order
    {
        $this->ticker = $ticker;

        return $this;
    }
}
