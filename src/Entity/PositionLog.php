<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class PositionLog.
 *
 * @ORM\Table(
 *     name="position_log",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="position_log_un_guid", columns={"guid"}),
 *     },
 *     indexes={
 *          @ORM\Index(name="position_log_ix_posiiton_source", columns={"position_id", "source_id"}),
 *          @ORM\Index(name="position_log_ix_position_order", columns={"position_id", "order_id"}),
 *     }
 * )
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class PositionLog extends AbstractGuidEntity
{
    /**
     * @var float
     *
     * @ORM\Column(name="average_filled_price", type="float", nullable=false)
     */
    private float $averageFilledPrice = 0.00;

    /**
     * @var float
     *
     * @ORM\Column(name="change_today", type="float", nullable=false)
     */
    private float $changeToday = 0.00;

    /**
     * @var float
     *
     * @ORM\Column(name="cost_basis", type="float", nullable=false)
     */
    private float $costBasis = 0.00;

    /**
     * @var float
     *
     * @ORM\Column(name="current_price", type="float", nullable=false)
     */
    private float $currentPrice = 0.00;

    /**
     * @var Order
     *
     * @ORM\OneToOne(targetEntity="Order", inversedBy="positionLog", fetch="LAZY")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=false)
     */
    private Order $order;

    /**
     * @var Position
     *
     * @ORM\ManyToOne(targetEntity="Position", inversedBy="positionLogs", fetch="LAZY")
     * @ORM\JoinColumn(name="position_id", referencedColumnName="id", nullable=false)
     */
    private Position $position;

    /**
     * @var float
     * @ORM\Column(name="quantity", type="float", nullable=false)
     */
    private float $quantity = 0.00;

    /**
     * @var string
     *
     * @ORM\Column(name="side", type="enumSideType", length=20, nullable=false)
     */
    private string $side;

    /**
     * @var Source
     *
     * @ORM\ManyToOne(targetEntity="Source", inversedBy="positionLogs", fetch="LAZY")
     * @ORM\JoinColumn(name="source_id", referencedColumnName="id", nullable=false)
     */
    private Source $source;

    /**
     * @var float
     *
     * @ORM\Column(name="unrealized_profit", type="float", nullable=false)
     */
    private float $unrealizedProfit = 0.00;
    /**
     * @var float
     *
     * @ORM\Column(name="unrealized_profit_percentage", type="float", nullable=false)
     */
    private float $unrealizedProfitPercentage = 0.00;

    /**
     * PositionLog constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return float
     */
    public function getAverageFilledPrice(): float
    {
        return $this->averageFilledPrice;
    }

    /**
     * @param float $averageFilledPrice
     *
     * @return PositionLog
     */
    public function setAverageFilledPrice(float $averageFilledPrice): PositionLog
    {
        $this->averageFilledPrice = $averageFilledPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getChangeToday(): float
    {
        return $this->changeToday;
    }

    /**
     * @param float $changeToday
     *
     * @return PositionLog
     */
    public function setChangeToday(float $changeToday): PositionLog
    {
        $this->changeToday = $changeToday;

        return $this;
    }

    /**
     * @return float
     */
    public function getCostBasis(): float
    {
        return $this->costBasis;
    }

    /**
     * @param float $costBasis
     *
     * @return PositionLog
     */
    public function setCostBasis(float $costBasis): PositionLog
    {
        $this->costBasis = $costBasis;

        return $this;
    }

    /**
     * @return float
     */
    public function getCurrentPrice(): float
    {
        return $this->currentPrice;
    }

    /**
     * @param float $currentPrice
     *
     * @return PositionLog
     */
    public function setCurrentPrice(float $currentPrice): PositionLog
    {
        $this->currentPrice = $currentPrice;

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
     * @return PositionLog
     */
    public function setOrder(Order $order): PositionLog
    {
        $this->order = $order;

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
     * @return PositionLog
     */
    public function setPosition(Position $position): PositionLog
    {
        $this->position = $position;

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
     * @return PositionLog
     */
    public function setQuantity(float $quantity): PositionLog
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
     * @return PositionLog
     */
    public function setSide(string $side): PositionLog
    {
        $this->side = $side;

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
     * @return PositionLog
     */
    public function setSource(Source $source): PositionLog
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return float
     */
    public function getUnrealizedProfit(): float
    {
        return $this->unrealizedProfit;
    }

    /**
     * @param float $unrealizedProfit
     *
     * @return PositionLog
     */
    public function setUnrealizedProfit(float $unrealizedProfit): PositionLog
    {
        $this->unrealizedProfit = $unrealizedProfit;

        return $this;
    }

    /**
     * @return float
     */
    public function getUnrealizedProfitPercentage(): float
    {
        return $this->unrealizedProfitPercentage;
    }

    /**
     * @param float $unrealizedProfitPercentage
     *
     * @return PositionLog
     */
    public function setUnrealizedProfitPercentage(float $unrealizedProfitPercentage): PositionLog
    {
        $this->unrealizedProfitPercentage = $unrealizedProfitPercentage;

        return $this;
    }
}
