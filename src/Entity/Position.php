<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * class AlpacaPosition.
 *
 * @ORM\Table(
 *        name="position",
 *        uniqueConstraints={
 * 			@ORM\UniqueConstraint(name="position_un_guid", columns={"guid"}),
 *        },
 *      indexes={
 *          @ORM\Index(name="position_ix_account_ticker", columns={"account_id", "ticker_id"}),
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\PositionRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class Position extends AbstractGuidEntity
{
    /**
     * @var Account
     *
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="positions", fetch="LAZY")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=false)
     */
    private Account $account;

    /**
     * @var float
     *
     * @ORM\Column(name="avg_filled_price", type="float", nullable=false, options={"default"=0.00})
     */
    private float $averageFilledPrice = 0.00;

    /**
     * @var ArrayCollection|Order[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Order", mappedBy="position", fetch="LAZY")
     */
    private $orders;

    /**
     * @var ArrayCollection|PositionLog[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="PositionLog", mappedBy="position", fetch="LAZY", cascade={"persist", "remove"})
     */
    private $positionLogs;

    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="float", nullable=false, options={"default"=0.00})
     */
    private float $quantity;

    /**
     * @var ArrayCollection|Source[]|PersistentCollection
     *
     * @ORM\ManyToMany(targetEntity="Source", mappedBy="positions", fetch="LAZY")
     */
    private $sources;

    /**
     * @var Ticker
     *
     * @ORM\ManyToOne(targetEntity="Ticker", inversedBy="positions", fetch="LAZY")
     * @ORM\JoinColumn(name="ticker_id", referencedColumnName="id", nullable=false)
     */
    private Ticker $ticker;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="enumPositionType", length=30, nullable=false)
     */
    private string $type;

    /**
     * AlpacaPosition constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->positionLogs = new ArrayCollection();
        $this->sources = new ArrayCollection();

        parent::__construct();
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
     * @return Position
     */
    public function setAccount(Account $account): Position
    {
        $this->account = $account;

        return $this;
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
     * @return Position
     */
    public function setAverageFilledPrice(float $averageFilledPrice): Position
    {
        $this->averageFilledPrice = $averageFilledPrice;

        return $this;
    }

    /**
     * @param Order $order
     *
     * @return $this
     */
    public function addOrder(Order $order): Position
    {
        $this->orders->add($order);

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
    public function setOrders($orders): Position
    {
        $this->orders = $orders;

        return $this;
    }

    /**
     * @return PositionLog[]|ArrayCollection|PersistentCollection
     */
    public function getPositionLogs()
    {
        return $this->positionLogs;
    }

    /**
     * @param PositionLog[]|ArrayCollection|PersistentCollection $positionLogs
     *
     * @return Position
     */
    public function setPositionLogs($positionLogs): Position
    {
        $this->positionLogs = $positionLogs;

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
     * @return Position
     */
    public function setQuantity(float $quantity): Position
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return Source[]|ArrayCollection|PersistentCollection
     */
    public function getSources()
    {
        return $this->sources;
    }

    /**
     * @param Source[]|ArrayCollection|PersistentCollection $sources
     *
     * @return Position
     */
    public function setSources($sources): Position
    {
        $this->sources = $sources;

        return $this;
    }

    /**
     * @return Ticker|null
     */
    public function getTicker(): ?Ticker
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
     * @return Position
     */
    public function setType(string $type): Position
    {
        $this->type = $type;

        return $this;
    }
}
