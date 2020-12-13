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
 * class Brokerage.
 *
 * @ORM\Table(
 * 		name="brokerage",
 * 		uniqueConstraints={
 * 			@ORM\UniqueConstraint(name="brokerage_un_guid", columns={"guid"}),
 * 			@ORM\UniqueConstraint(name="brokerage_un_context", columns={"context"})
 * 		}
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\BrokerageRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class Brokerage extends AbstractGuidEntity
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="context", type="string", length=100, nullable=false)
     */
    private $context;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var string|null
     *
     * @ORM\Column(name="api_document_url", length=255, nullable=true)
     */
    private $apiDocumentUrl;

    /**
     * @var ArrayCollection}Account[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Account", mappedBy="brokerage", fetch="LAZY")
     */
    private $accounts;

    /**
     * @var ArrayCollection|Order[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Order", mappedBy="brokerage", fetch="LAZY")
     */
    private $orders;

    /**
     * @var ArrayCollection|Ticker[]|PersistentCollection
     *
     * @ORM\ManyToMany(targetEntity="Ticker", inversedBy="brokerages", fetch="LAZY")
     */
    private $tickers;

    /**
     * Brokerage constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->accounts = new ArrayCollection();
        $this->orders = new ArrayCollection();
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
     * @return Brokerage
     */
    public function setName(string $name): Brokerage
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getContext(): string
    {
        return $this->context;
    }

    /**
     * @param string $context
     */
    public function setContext(string $context): Brokerage
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Brokerage
     */
    public function setDescription(string $description): Brokerage
    {
        $this->description = $description;

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
     * @return Brokerage
     */
    public function setUrl(string $url): Brokerage
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiDocumentUrl(): string
    {
        return $this->apiDocumentUrl;
    }

    /**
     * @param string $apiDocumentUrl
     *
     * @return Brokerage
     */
    public function setApiDocumentUrl(string $apiDocumentUrl): Brokerage
    {
        $this->apiDocumentUrl = $apiDocumentUrl;

        return $this;
    }

    /**
     * @return ArrayCollection|Account[]|PersistentCollection
     */
    public function getAccounts()
    {
        return $this->accounts->getValues();
    }

    /**
     * @param ArrayCollection|Account[]|PersistentCollection $accounts
     */
    public function setAccounts($accounts): Brokerage
    {
        $this->accounts = $accounts;

        return $this;
    }

    /**
     * @return ArrayCollection|Order[]|PersistentCollection
     */
    public function getOrders()
    {
        return $this->orders->getValues();
    }

    /**
     * @param Order[]|ArrayCollection|PersistentCollection $orders
     */
    public function setOrders($orders): void
    {
        $this->orders = $orders;
    }

    /**
     * @return Ticker[]|ArrayCollection|PersistentCollection
     */
    public function getTickers()
    {
        return $this->tickers;
    }

    /**
     * @param Ticker[]|ArrayCollection|PersistentCollection $tickers
     */
    public function setTickers($tickers): void
    {
        $this->tickers = $tickers;
    }
}
