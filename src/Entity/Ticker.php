<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
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
     * @var string
     *
     * @ORM\Column(name="ticker", type="string", length=10, nullable=false)
     */
    private $ticker;

    /**
     * @var string
     */
    private $updated;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=200, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="market", type="string", length=25, nullable=false)
     */
    private $market;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=5, nullable=false)
     */
    private $currency;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active;

    /**
     * @var string;
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

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
     * @return TickerType|null
     */
    public function getType(): ?TickerType
    {
        return $this->type;
    }

    /**
     * @param TickerType|null $type
     *
     * @return $this
     */
    public function setType(TickerType $type = null): Ticker
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param string $updated
     *
     * @return Ticker
     */
    public function setUpdated(string $updated): Ticker
    {
        $this->setModifiedAt(new \DateTime($updated));

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated(): \DateTime
    {
        return $this->getModifiedAt();
    }
}
