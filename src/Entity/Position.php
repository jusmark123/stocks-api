<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * class Position.
 *
 * @ORM\Table(
 * 		name="position",
 * 		uniqueConstraints={
 * 			@ORM\UniqueConstraint(name="position_un_guid", columns={"guid"}),
 * 		},
 * 		indexes={}
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\PositionRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class Position extends AbstractGuidEntity
{
    /**
     * @var float
     *
     * @ORM\Column(name="change_today", type="float", nullable=false, options={"default": 0.00})
     */
    private $changeToday;

    /**
     * @var float
     * @ORM\Column(name="cost_basis", type="float", nullable=false, options={"default": 0.00})
     */
    private $costBasis;

    /**
     * @var string
     * @ORM\Column(name="exchange", type="string", nullable=false)
     */
    private $exchange;

    /**
     * @var float
     * @ORM\Column(name="market_value", type="float", nullable=false, options={"default": 0.00})
     */
    private $marketValue;

    /**
     * @var int
     * @ORM\Column(name="quantity", type="integer", nullable=false, options={"default": 0})
     */
    private $qty;

    /**
     * @var Account
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="positions")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $account;

    /**
     * @var ArrayCollection|Order[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Order", mappedBy="position", fetch="LAZY")
     */
    private $orders;

    /**
     * @var PositionSideType
     * @ORM\ManyToOne(targetEntity="PositionSideType")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="position_side_type_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $positionSideType;

    /**
     * @var Source
     *
     * @ORM\ManyToOne(targetEntity="Source", inversedBy="positions", fetch="LAZY")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="source_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $source;

    /**
     * @var Ticker
     *
     * @ORM\ManyToOne(targetEntity="Ticker", fetch="LAZY")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="ticker_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $ticker;

    /**
     * Position constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->orders = new ArrayCollection();
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
    public function setQty(int $qty): Position
    {
        $this->qty = $qty;

        return $this;
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
     * @return $this
     */
    public function setAccount(Account $account): Position
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return string
     */
    public function getExchange(): string
    {
        return $this->exchange;
    }

    /**
     * @param string $exchange
     *
     * @return $this
     */
    public function setExchange(string $exchange): Position
    {
        $this->exchange = $exchange;

        return $this;
    }

    /**
     * @return PositionSideType
     */
    public function getPositionSideType(): PositionSideType
    {
        return $this->positionSideType;
    }

    /**
     * @param PositionSideType $positionSideType
     *
     * @return $this
     */
    public function setPositionSideType(PositionSideType $positionSideType): Position
    {
        $this->positionSideType = $positionSideType;

        return $this;
    }

    /**
     * @return float
     */
    public function getMarketValue(): float
    {
        return $this->marketValue;
    }

    /**
     * @param float $marketValue
     *
     * @return $this
     */
    public function setMarketValue(float $marketValue): Position
    {
        $this->marketValue = $marketValue;

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
     * @return $this
     */
    public function setChangeToday(float $changeToday): Position
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
     * @return $this
     */
    public function setCostBasis(float $costBasis): Position
    {
        $this->costBasis = $costBasis;

        return $this;
    }

    /**
     * @return Order[]|ArrayCollection|PersistentCollection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param Order[]|ArrayCollection|PersistentCollection $orders
     *
     * @return Position
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;

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
     * @return Position
     */
    public function setSource(Source $source): Position
    {
        $this->source = $source;

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
     * @return Position
     */
    public function setTicker(Ticker $ticker): Position
    {
        $this->ticker = $ticker;

        return $this;
    }
}
