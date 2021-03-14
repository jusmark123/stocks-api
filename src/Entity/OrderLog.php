<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use App\DTO\Brokerage\BrokerageOrderEventInterface;
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
     * @var float
     *
     * @ORM\Column(name="amount_usd", type="float", nullable=false, options={"default"=0.00})
     */
    private float $amountUsd = 0.00;

    /**
     * @var string
     *
     * @ORM\Column(name="event", type="text", nullable=false)
     */
    private string $event;

    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="float", nullable=false, options={"default"=0.00})
     */
    private float $filledQuantity;

    /**
     * @var string
     *
     * @ORM\Column(name="side", type="enumSideType", length=20, nullable=false)
     */
    private string $side;

    /**
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="orderLogs", fetch="LAZY")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=false)
     */
    private Order $order;

    /**
     * @var OrderStatusType
     *
     * @ORM\ManyToOne(targetEntity="OrderStatusType", fetch="LAZY")
     * @ORM\JoinColumn(name="order_status_id", referencedColumnName="id", nullable=false)
     */
    private OrderStatusType $orderStatus;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime", nullable=false)
     */
    private \DateTime $timestamp;

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
     * @return OrderLog
     */
    public function setAmountUsd(float $amountUsd): OrderLog
    {
        $this->amountUsd = $amountUsd;

        return $this;
    }

    /**
     * @return BrokerageOrderEventInterface
     */
    public function getEvent(): BrokerageOrderEventInterface
    {
        return unserialize($this->event);
    }

    /**
     * @param BrokerageOrderEventInterface $event
     *
     * @return OrderLog
     */
    public function setEvent(BrokerageOrderEventInterface $event): OrderLog
    {
        $this->event = serialize($event);

        return $this;
    }

    /**
     * @return float
     */
    public function getFilledQuantity(): float
    {
        return $this->filledQuantity;
    }

    /**
     * @param float $filledQuantity
     *
     * @return OrderLog
     */
    public function setFilledQuantity(float $filledQuantity): OrderLog
    {
        $this->filledQuantity = $filledQuantity;

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

    /**
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * @param string|\DateTime $timestamp
     *
     * @throws \Exception
     *
     * @return OrderLog
     */
    public function setTimestamp($timestamp): OrderLog
    {
        $this->timestamp = new \DateTime($timestamp);

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
     * @return OrderLog
     */
    public function setOrder(Order $order): OrderLog
    {
        $this->order = $order;

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
     * @return OrderLog
     */
    public function setOrderStatus(OrderStatusType $orderStatus): OrderLog
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }
}
