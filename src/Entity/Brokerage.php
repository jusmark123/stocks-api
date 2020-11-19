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
 * 			@ORM\UniqueConstraint(name="brokerage_un_guid", columns={"guid"})
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
     * @ORM\Column(name="api_documenation_url", length=255, nullable=true)
     */
    private $apiDocumentUrl;

    /**
     * @var ArrayCollection}Account[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Account", mappedBy="brokerage", fetch="LAZY")
     */
    private $accounts;

    /**
     * @var ArrayCollection|Orders[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Order", mappedBy="brokerage", fetch="LAZY")
     */
    private $orders;

    /**
     * Constructor.
     */
    public function __construct()
    {
        // $this->__guidConstructor();
        $this->accounts = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Brokerage
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Brokerage
    {
        $this->description = $description;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): Brokerage
    {
        $this->url = $url;

        return $this;
    }

    public function getApiDocumentationUrl(): string
    {
        return $this->getApiDocumentationUrl;
    }

    public function setApiDocumentationUrl(string $apiDocumentationUrl): Brokerage
    {
        $this->apiDocumentationUrl = $apiDocumentationUrl;

        return $this;
    }

    /**
     * @return Account[]|ArrayCollection|PersistentCollection
     */
    public function getAccounts()
    {
        return $this->accounts->getValues();
    }

    /**
     * @param Account[]|ArrayCollection|PersistentCollection $accounts
     */
    public function setAccounts($accounts): Brokerage
    {
        $this->accounts = $accounts;

        return $this;
    }

    public function getOrders(): ArrayCollection
    {
        return $this->orders->getValues();
    }

    public function setOrders(ArrayCollection $orders): Brokerage
    {
        $this->orders = $orders;

        return $this;
    }
}
