<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use App\DTO\Brokerage\BrokerageTickerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Ticker.
 *
 * @ORM\Table(
 *      name="ticker",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="ticker_un_guid", columns={"guid"}),
 *          @ORM\UniqueConstraint(name="ticker_un_ticker", columns={"ticker"}),
 *      },
 *      indexes={
 *          @ORM\Index(name="ticker_ix_name", columns={"name"}),
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\TickerRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class Ticker extends AbstractGuidEntity
{
    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private bool $active;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=5, nullable=false)
     */
    private string $currency;

    /**
     * @var string|null
     *
     * @ORM\Column(name="exchange", type="string", length=100, nullable=true)
     */
    private ?string $exchange;

    /**
     * @var string
     *
     * @ORM\Column(name="market", type="string", length=25, nullable=false)
     */
    private string $market;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private string $name;

    /**
     * @var TickerSector|null
     *
     * @ORM\ManyToOne(targetEntity="TickerSector", inversedBy="tickers", fetch="LAZY", cascade="persist")
     * @ORM\JoinColumn(name="sector_id", referencedColumnName="id", nullable=true)
     */
    private ?TickerSector $sector = null;

    /**
     * @var string
     *
     * @ORM\Column(name="ticker", type="string", length=20, nullable=false)
     */
    private string $ticker;

    /**
     *  @var BrokerageTickerInterface|null
     */
    private ?BrokerageTickerInterface $tickerInfo;

    /**
     * @deprecated
     *
     * @var TickerType|null
     *
     * @ORM\ManyToOne(targetEntity="TickerType")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="ticker_type_id", referencedColumnName="id", nullable=true)
     * })
     */
    private ?TickerType $tickerType;

    /**
     * @var ArrayCollection|Brokerage[]|PersistentCollection
     *
     * @ORM\ManyToMany(targetEntity="Brokerage", inversedBy="tickers", cascade={"persist","remove"})
     * @ORM\JoinTable(name="brokerage_ticker")
     */
    private $brokerages;

    /**
     * @var ArrayCollection|Position[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Position", mappedBy="ticker", cascade={"persist", "remove"})
     */
    private $positions;

    /**
     * @var ArrayCollection|Order[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Order", mappedBy="ticker", cascade={"persist", "remove"})
     */
    private $orders;

    /**
     * Ticker constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->brokerages = new ArrayCollection();
        $this->positions = new ArrayCollection();
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return Ticker
     */
    public function setActive(bool $active): Ticker
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string|null $currency
     *
     * @return Ticker
     */
    public function setCurrency(?string $currency): Ticker
    {
        $this->currency = $currency ?? 'USD';

        return $this;
    }

    /**
     * @return string|null
     */
    public function getExchange(): ?string
    {
        return $this->exchange;
    }

    /**
     * @param string|null $exchange
     *
     * @return Ticker
     */
    public function setExchange(?string $exchange = null): Ticker
    {
        $this->exchange = $exchange;

        return $this;
    }

    /**
     * @return string
     */
    public function getMarket(): string
    {
        return $this->market;
    }

    /**
     * @param string $market
     *
     * @return Ticker
     */
    public function setMarket(string $market): Ticker
    {
        $this->market = $market;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Ticker
     */
    public function setName(string $name): Ticker
    {
        $this->name = $name;

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
     * @return Ticker
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;

        return $this;
    }

    /**
     * @return TickerSector|null
     */
    public function getSector(): ?TickerSector
    {
        return $this->sector;
    }

    /**
     * @param TickerSector|null $sector
     *
     * @return Ticker
     */
    public function setSector(?TickerSector $sector): Ticker
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * @return string
     */
    public function getTicker(): string
    {
        return $this->ticker;
    }

    /**
     * @param string $ticker
     *
     * @return Ticker
     */
    public function setTicker(string $ticker): Ticker
    {
        $this->ticker = $ticker;

        return $this;
    }

    /**
     * @return BrokerageTickerInterface|null
     */
    public function getTickerInfo(): ?BrokerageTickerInterface
    {
        return $this->tickerInfo;
    }

    /**
     * @param BrokerageTickerInterface|null $tickerInfo
     *
     * @return Ticker
     */
    public function setTickerInfo(?BrokerageTickerInterface $tickerInfo): Ticker
    {
        $this->tickerInfo = $tickerInfo;

        return $this;
    }

    /**
     * @deprecated
     *
     * @return TickerType|null
     */
    public function getTickerType(): ?TickerType
    {
        return $this->tickerType;
    }

    /**
     * @deprecated
     *
     * @param TickerType|null $tickerType
     *
     * @return Ticker
     */
    public function setTickerType(?TickerType $tickerType): Ticker
    {
        $this->tickerType = $tickerType;

        return $this;
    }

    /**
     * @param Brokerage $brokerage
     *
     * @return $this
     */
    public function addBrokerage(Brokerage $brokerage): Ticker
    {
        $this->brokerages->add($brokerage);

        return $this;
    }

    /**
     * @return Brokerage[]|ArrayCollection|PersistentCollection
     */
    public function getBrokerages()
    {
        return $this->brokerages;
    }

    /**
     * @param Brokerage[]|ArrayCollection|PersistentCollection $brokerages
     *
     * @return Ticker
     */
    public function setBrokerages($brokerages)
    {
        $this->brokerages = $brokerages;

        return $this;
    }

    /**
     * @return Position[]|ArrayCollection|PersistentCollection
     */
    public function getPositions()
    {
        return $this->positions;
    }

    /**
     * @param Position[]|ArrayCollection|PersistentCollection $positions
     *
     * @return Ticker
     */
    public function setPositions($positions)
    {
        $this->positions = $positions;

        return $this;
    }
}
