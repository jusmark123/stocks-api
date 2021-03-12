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
 * class AlpacaPosition.
 *
 * @ORM\Table(
 * 		name="position",
 * 		uniqueConstraints={
 * 			@ORM\UniqueConstraint(name="position_un_guid", columns={"guid"}),
 *          @ORM\UniqueConstraint(name="position_un_source_type", columns={"source_id", "ticker_id"}),
 * 		}
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
     * @var ArrayCollection|Order[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Order", mappedBy="position", fetch="LAZY")
     */
    private $orders;

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
     * @var string
     *
     * @ORM\Column(name="type", type="enumPositionType", length=30, nullable=false)
     */
    private string $type;

    /**
     * @var Source|null
     *
     * @ORM\ManyToOne(targetEntity="Source", inversedBy="positions", fetch="LAZY")
     * @ORM\JoinColumn(name="source_id", referencedColumnName="id", nullable=false)
     */
    private ?Source $source = null;

    /**
     * @var Ticker|null
     *
     * @ORM\ManyToOne(targetEntity="Ticker", inversedBy="positions", fetch="LAZY")
     * @ORM\JoinColumn(name="ticker_id", referencedColumnName="id", nullable=false)
     */
    private Ticker $ticker;

    /**
     * AlpacaPosition constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->orders = new ArrayCollection();
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
     * @return string
     */
    public function getSide(): string
    {
        return $this->side;
    }

    /**
     * @param string $side
     *
     * @return Position
     */
    public function setSide(string $side): Position
    {
        $this->side = $side;

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

    /**
     * @return Source|null
     */
    public function getSource(): ?Source
    {
        return $this->source;
    }

    /**
     * @param Source|null $source
     *
     * @return Position
     */
    public function setSource(?Source $source): Position
    {
        $this->source = $source;

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
}
