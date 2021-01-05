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
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=5, nullable=false)
     */
    private $currency;

    /**
     * @var string|null
     *
     * @ORM\Column(name="exchange", type="string", length=100, nullable=true)
     */
    private $exchange;

    /**
     * @var string
     *
     * @ORM\Column(name="market", type="string", length=25, nullable=false)
     */
    private $market;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sector", type="string", length=100, nullable=true)
     */
    private $sector;

    /**
     * @var string
     *
     * @ORM\Column(name="ticker", type="string", length=10, nullable=false)
     */
    private $ticker;

    /**
     *  @var TickerInterface|null
     */
    private $tickerInfo;

    /**
     * @var TickerType|null
     *
     * @ORM\ManyToOne(targetEntity="TickerType")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="ticker_type_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $tickerType;

    /**
     * @var ArrayCollection|Brokerage[]|PersistentCollection
     *
     * @ORM\ManyToMany(targetEntity="Brokerage", inversedBy="tickers", cascade={"persist","remove"})
     * @ORM\JoinTable(name="brokerage_ticker")
     */
    private $brokerages;

    /**
     * Ticker constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->brokerages = new ArrayCollection();
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
     * @param string $currency
     *
     * @return Ticker
     */
    public function setCurrency(string $currency): Ticker
    {
        $this->currency = $currency;

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
     * @return string|null
     */
    public function getSector(): ?string
    {
        return $this->sector;
    }

    /**
     * @param string|null $sector
     *
     * @return Ticker
     */
    public function setSector(?string $sector): Ticker
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
     * @return TickerInterface|null
     */
    public function getTickerInfo(): ?TickerInterface
    {
        return $this->tickerInfo;
    }

    /**
     * @param TickerInterface|null $tickerInfo
     *
     * @return Ticker
     */
    public function setTickerInfo(?TickerInterface $tickerInfo): Ticker
    {
        $this->tickerInfo = $tickerInfo;

        return $this;
    }

    /**
     * @return TickerType|null
     */
    public function getTickerType(): ?TickerType
    {
        return $this->tickerType;
    }

    /**
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
}
