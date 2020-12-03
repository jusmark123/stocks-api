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
 * Account.
 *
 * @ORM\Table(
 *		name="account",
 *		uniqueConstraints={
 *			@ORM\UniqueConstraint(name="account_un_guid", columns={"guid"})
 *		},
 *		indexes={
 *			@ORM\Index(name="account_ix_account_status_type_id", columns={"account_status_type_id"})
 *		},
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\AccountRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class Account extends AbstractGuidEntity
{
    /**
     * @var string|null
     * @ORM\Column(name="account_number", type="string", length=20, nullable=true)
     */
    private $accountNumber;

    /**
     * @var AccountStatusType
     * @ORM\ManyToOne(targetEntity="AccountStatusType", fetch="LAZY")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="account_status_type_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $accountStatusType;

    /**
     * @var string
     *
     * @ORM\Column(name="api_key", type="string", length=100, nullable=false)
     */
    private $apiKey;

    /**
     * @var string
     *
     * @ORM\Column(name="api_secret", type="string", length=100, nullable=false)
     */
    private $apiSecret;

    /**
     * @var string|null
     * @ORM\Column(name="api_endpoint_url", type="string", length=150, nullable=false)
     */
    private $apiEndpointUrl;

    /**
     * @var Brokerage
     *
     * @ORM\ManyToOne(targetEntity="Brokerage", inversedBy="accounts", fetch="LAZY")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="brokerage_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $brokerage;

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
     * @var ArrayCollection|Job[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Job", mappedBy="account", fetch="LAZY")
     */
    private $jobs;

    /**
     * @var ArrayCollection|User[]|PersistentCollection
     *
     * @ORM\ManyToMany(targetEntity="User", inversedBy="accounts", fetch="LAZY")
     * @ORM\JoinTable(name="account_users",
     * 		joinColumns={@ORM\JoinColumn(name="account_id", referencedColumnName="id")},
     * 		inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    private $users;

    /**
     * @var ArrayCollection|Position[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Position", mappedBy="account", fetch="LAZY")
     */
    private $positions;

    /**
     * @var ArrayCollection|Order[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Order", mappedBy="account", fetch="LAZY")
     */
    private $orders;

    /**
     * Account constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->jobs = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->positions = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * @return AccountStatusType
     */
    public function getAccountStatusType(): AccountStatusType
    {
        return $this->accountStatusType;
    }

    /**
     * @param AccountStatusType $accountStatusType
     *
     * @return Account
     */
    public function setAccountStatusType(AccountStatusType $accountStatusType): Account
    {
        $this->accountStatusType = $accountStatusType;

        return $this;
    }

    /**
     * @return string $apiKey
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     *
     * @return Account
     */
    public function setApiKey(string $apiKey): Account
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @return string $apiSecret
     */
    public function getApiSecret(): string
    {
        return $this->apiSecret;
    }

    /**
     * @param string $apiSecret
     *
     * @return Account
     */
    public function setApiSecret(string $apiSecret): Account
    {
        $this->apiSecret = $apiSecret;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiEndpointUrl(): string
    {
        return $this->apiEndpointUrl;
    }

    /**
     * @param string $apiEndpointUrl
     *
     * @return Account
     */
    public function setApiEndpointUrl(string $apiEndpointUrl): Account
    {
        $this->apiEndpointUrl = $apiEndpointUrl;

        return $this;
    }

    /**
     * @return Brokerage
     */
    public function getBrokerage(): Brokerage
    {
        return $this->brokerage;
    }

    /**
     * @param Brokerage $brokerage
     *
     * @return Account
     */
    public function setBrokerage(Brokerage $brokerage): Account
    {
        $this->brokerage = $brokerage;

        return $this;
    }

    /**
     * @return string $description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param  string
     *
     * @return Account
     */
    public function setDescription(string $description = null): Account
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string $name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Account
     */
    public function setName(string $name): Account
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param User $user
     *
     * @return Account
     */
    public function addUser(User $user): Account
    {
        $this->users->add($user);

        return $this;
    }

    /**
     * @return ArrayCollection|User[]|PersistentCollection $users
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User $user
     *
     * @return Account
     */
    public function removeUser(User $user): Account
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @param ArrayCollection|User[]|PersistentCollection $users
     */
    public function setUsers(array $users): Account
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @param Position $position
     *
     * @return Account
     */
    public function addPosition(Position $position): Account
    {
        $this->positions->add($position);

        return $this;
    }

    /**
     * @return ArrayCollection|Position[]|PersistentCollection
     */
    public function getPositions()
    {
        return $this->positions;
    }

    /**
     * @param ArrayCollection|Position[]|PersistentCollection $positions
     */
    public function setPositions(array $positions): Account
    {
        $this->positions = $positions;

        return $this;
    }

    /**
     * @return Account`
     */
    public function removePosition(Position $position): Account
    {
        $this->position->removeElement($position);

        return $this;
    }

    public function addOrder(Order $order): Source
    {
        $this->orders->add($order);

        return $this;
    }

    /**
     * @return ArrayCollection|Order[]|PersistentCollection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param Order $order
     *
     * @return Source
     */
    public function removeOrder(Order $order): Source
    {
        $this->orders->removeElement($order);

        return $this;
    }

    /**
     * @param ArrayCollection|Order[]|PersistentCollection $order
     */
    public function setOrders(array $order): Source
    {
        $this->orders = $orders;

        return $this;
    }

    /**
     * @return ArrayCollection|Job[]|PersistentCollection
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    /**
     * @param ArrayCollection|Job[]|PersistentCollection $jobs
     *
     * @return Account
     */
    public function setJobs($jobs): Account
    {
        $this->jobs = $jobs;

        return $this;
    }
}
