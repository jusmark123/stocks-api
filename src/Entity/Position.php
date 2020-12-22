<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use App\DTO\Brokerage\TickerInterface;
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
 * 		indexes={
 *          @ORM\Index(name="position_ix_account_id", columns={"account_id"}),
 *          @ORM\Index(name="position_ix_source_id", columns={"source_id"}),
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\PositionRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class Position extends AbstractGuidEntity
{
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
     * @var string
     *
     * @ORM\Column(name="side", type="string", nullable=false)
     */
    private $side;

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
     * @var string
     *
     * @ORM\Column(name="symbol", type="string", length=10, nullable=false)
     */
    private $symbol;

    /**
     * @var TickerInterface|null
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
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     *
     * @return Position
     */
    public function setSymbol(string $symbol): Position
    {
        $this->symbol = $symbol;

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
     * @return TickerInterface|null
     */
    public function getTicker(): ?TickerInterface
    {
        return $this->ticker;
    }

    /**
     * @param TickerInterface|null $ticker
     *
     * @return Position
     */
    public function setTicker(?TickerInterface $ticker): Position
    {
        $this->ticker = $ticker;

        return $this;
    }
}
