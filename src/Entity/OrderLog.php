<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use App\DTO\Brokerage\BrokerageOrderStatusInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class OrderLog.
 *
 * @ORM\Table(
 *     name="order_log",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="order_log_un_guid", columns={"guid"})
 *     },
 *     indexes={
 *          @ORM\Index(name="order_log_ix_order_id", columns={"order_id"})
 *     }
 * )
 * @ORM\Entity()
 */
class OrderLog extends AbstractGuidEntity
{
    /**
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="orderLogs", fetch="LAZY")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=false)
     */
    private Order $order;

    /**
     * @var string
     *
     * @ORM\Column(name="order_type", type="enumOrderType", length=25, nullable=false)
     */
    private string $orderType;

    /**
     * @var BrokerageOrderStatusInterface
     */
    private BrokerageOrderStatusInterface $orderStatus;

    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="float", nullable=false, options={"default"=0.00})
     */
    private float $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="side", type="enumSideType", length=20, nullable=false)
     */
    private string $side;

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
     * @return OrderLog
     */
    public function setOrder(Order $order): OrderLog
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderType(): string
    {
        return $this->orderType;
    }

    /**
     * @param string $orderType
     *
     * @return OrderLog
     */
    public function setOrderType(string $orderType): OrderLog
    {
        $this->orderType = $orderType;

        return $this;
    }

    /**
     * @return BrokerageOrderStatusInterface
     */
    public function getOrderStatus(): BrokerageOrderStatusInterface
    {
        return $this->orderStatus;
    }

    /**
     * @param BrokerageOrderStatusInterface $orderStatus
     *
     * @return OrderLog
     */
    public function setOrderStatus(BrokerageOrderStatusInterface $orderStatus): OrderLog
    {
        $this->orderStatus = $orderStatus;

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
     * @return OrderLog
     */
    public function setQuantity(float $quantity): OrderLog
    {
        $this->quantity = $quantity;

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
     * @return OrderLog
     */
    public function setSide(string $side): OrderLog
    {
        $this->side = $side;

        return $this;
    }
}
