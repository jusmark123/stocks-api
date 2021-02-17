<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
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
     * @ORM\Column(name="api_document_url", length=255, nullable=true)
     */
    private $apiDocumentUrl;

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
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="has_paper_accounts", type="boolean", nullable=false, options={"default"=false})
     */
    private $paperAccounts;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    // Subresources

    /**
     * @var ArrayCollection|Account[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Account", mappedBy="brokerage", fetch="LAZY", cascade={"persist","remove"})
     */
    private $accounts;

    /**
     * @var ArrayCollection|Order[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Order", mappedBy="brokerage", fetch="LAZY")
     */
    private $orders;

    /**
     * @var ArrayCollection|OrderStatusType[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="OrderStatusType", mappedBy="brokerage", fetch="LAZY")
     */
    private $orderStatusTypes;

    /**
     * @var ArrayCollection|OrderType[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="OrderType", mappedBy="brokerage", fetch="LAZY")
     */
    private $orderTypes;

    /**
     * @var ArrayCollection|Ticker[]|PersistentCollection
     *
     * @ORM\ManyToMany(targetEntity="Ticker", mappedBy="brokerages", fetch="LAZY")
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
        $this->tickers = new ArrayCollection();
        $this->orderTypes = new ArrayCollection();
        $this->orderStatusTypes = new ArrayCollection();
        $this->positionSideTypes = new ArrayCollection();
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
     * @return bool
     */
    public function hasPaperAccounts(): bool
    {
        return $this->paperAccounts;
    }

    /**
     * @param bool $paperAccounts
     *
     * @return Brokerage
     */
    public function setPaperAccounts(bool $paperAccounts): Brokerage
    {
        $this->paperAccounts = $paperAccounts;

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
     * @return ArrayCollection|\Doctrine\Common\Collections\Collection
     */
    public function getDefaultAccount()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('default', true))
            ->setFirstResult(0);

        return $this->accounts->matching($criteria)->first();
    }

    /**
     * @return ArrayCollection|Account[]|PersistentCollection
     */
    public function getAccounts()
    {
        return $this->accounts;
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
     * @return Order[]|ArrayCollection|PersistentCollection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param Order[]|ArrayCollection|PersistentCollection $orders
     *
     * @return Brokerage
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;

        return $this;
    }

    /**
     * @return OrderStatusType[]|ArrayCollection|PersistentCollection
     */
    public function getOrderStatusTypes()
    {
        return $this->orderStatusTypes;
    }

    /**
     * @param OrderStatusType[]|ArrayCollection|PersistentCollection $orderStatusTypes
     *
     * @return Brokerage
     */
    public function setOrderStatusTypes($orderStatusTypes)
    {
        $this->orderStatusTypes = $orderStatusTypes;

        return $this;
    }

    /**
     * @return OrderType[]|ArrayCollection|PersistentCollection
     */
    public function getOrderTypes()
    {
        return $this->orderTypes;
    }

    /**
     * @param OrderType[]|ArrayCollection|PersistentCollection $orderTypes
     *
     * @return Brokerage
     */
    public function setOrderTypes($orderTypes)
    {
        $this->orderTypes = $orderTypes;

        return $this;
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
     *
     * @return Brokerage
     */
    public function setTickers($tickers)
    {
        $this->tickers = $tickers;

        return $this;
    }
}
