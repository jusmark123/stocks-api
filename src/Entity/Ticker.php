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
 *          @ORM\UniqueConstraint(name="ticker_un_name", columns={"name"}),
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
     * @var ArrayCollection|Brokerage[]|PersistentCollection
     *
     * @ORM\ManyToMany(targetEntity="Brokerage", inversedBy="tickers", cascade={"persist","remove"})
     * @ORM\JoinTable(name="brokerage_ticker")
     */
    private $brokerages;

    /**
     * @var string
     *
     * @ORM\Column(name="exchange", type="string", length=100, nullable=false)
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
     * @ORM\Column(name="name", type="string", length=200, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="sector", type="string", length=100, nullable=false)
     */
    private $sector;

    /**
     * @var string
     *
     * @ORM\Column(name="symbol", type="string", length=10, nullable=false)
     */
    private $symbol;

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
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var string;
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

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
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return Ticker
     */
    public function setCountry(string $country): Ticker
    {
        $this->country = $country;

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
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return Ticker
     */
    public function setDescription(?string $description): Ticker
    {
        $this->description = $description;

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
     * @return string
     */
    public function getExchange(): string
    {
        return $this->exchange;
    }

    /**
     * @param string $exchange
     *
     * @return Ticker
     */
    public function setExchange(string $exchange): Ticker
    {
        $this->exchange = $exchange;

        return $this;
    }

    /**
     * @return string
     */
    public function getExchangeSymbol(): string
    {
        return $this->exchangeSymbol;
    }

    /**
     * @param string $exchangeSymbol
     *
     * @return Ticker
     */
    public function setExchangeSymbol(string $exchangeSymbol): Ticker
    {
        $this->exchangeSymbol = $exchangeSymbol;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Ticker
     */
    public function setUrl(string $url): Ticker
    {
        $this->url = $url;

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
     * @return Ticker
     */
    public function setSymbol(string $symbol): Ticker
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * @return string
     */
    public function getSector(): string
    {
        return $this->sector;
    }

    /**
     * @param string $sector
     *
     * @return Ticker
     */
    public function setSector(string $sector): Ticker
    {
        $this->sector = $sector;

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
    public function getType(): ?TickerType
    {
        return $this->type;
    }

    /**
     * @param TickerType|null $type
     *
     * @return Ticker
     */
    public function setType(?TickerType $type): Ticker
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return Ticker
     */
    public function setUpdated(\DateTime $updatedAt): Ticker
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @param Brokerage $brokerage
     *
     * @return $this
     */
    public function addBrokerage(Brokerage $brokerage)
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
     * @param Brokerage $brokeraga
     *
     * @return $this
     */
    public function removeBrokerage(Brokerage $brokeraga)
    {
        $this->brokerages->removeElement($brokeraga);

        return $this;
    }

    /**
     * @param Brokerage[]|ArrayCollection|PersistentCollection $brokerages
     */
    public function setBrokerages($brokerages): void
    {
        $this->brokerages = $brokerages;
    }
}
