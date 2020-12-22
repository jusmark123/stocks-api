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
 * 			@ORM\UniqueConstraint(name="position_un_symbol_account_id", columns={"symbol", "account_id"}),
 * 		},
 * 		indexes={
 * 			@ORM\Index(name="position_ix_symbol", columns={"symbol"})
 * 		}
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\PositionRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class Position extends AbstractGuidEntity
{
    /**
     * @var string
     * @ORM\Column(name="symbol", type="string", length=20, nullable=false)
     */
    private $symbol;

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
     * @var string
     * @ORM\Column(name="exchange", type="string", nullable=false)
     */
    private $exchange;

    /**
     * @var PositionSideType
     * @ORM\ManyToOne(targetEntity="PositionSideType")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="position_side_type_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $positionSideType;

    /**
     * @var float
     * @ORM\Column(name="market_value", type="float", nullable=false, options={"default": 0.00})
     */
    private $marketValue;

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
     * @var ArrayColections|Order[]|PersistentCollection
     * @ORM\OneToMany(targetEntity="Order", mappedBy="position", fetch="LAZY")
     * */
    private $orders;

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

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): Position
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getQty(): int
    {
        return $this->qty;
    }

    public function setQty(int $qty): Position
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): Position
    {
        $this->account = $account;

        return $this;
    }

    public function getExchange(): string
    {
        return $this->exchange;
    }

    public function setExchange(string $exchange): Position
    {
        $this->exchange = $exchange;

        return $this;
    }

    public function getPositionSideType(): PositionSideType
    {
        return $this->positionSideType;
    }

    public function setPositionSideType(PositionSideType $positionSideType): Position
    {
        $this->positionSideType = $positionSideType;

        return $this;
    }

    public function getMarketValue(): float
    {
        return $this->marketValue;
    }

    public function setMarketValue(float $marketValue): Position
    {
        $this->marketValue = $marketValue;

        return $this;
    }

    public function getChangeToday(): float
    {
        return $this->changeToday();
    }

    public function setChangeToday(float $changeToday): Position
    {
        $this->changeToday = $changeToday;

        return $this;
    }

    public function getCostBasis(): float
    {
        return $this->costBasis;
    }

    public function setCostBasis(float $costBasis): Position
    {
        $this->costBasis = $costBasis;

        return $this;
    }
}
