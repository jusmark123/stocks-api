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
     * @var AccountInfoInterface
     */
    private $accountInfo;
    /**
     * @var string
     * @ORM\Column(name="account_number", type="string", length=20, nullable=false)
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
     * @var Brokerage
     *
     * @ORM\ManyToOne(targetEntity="Brokerage", inversedBy="accounts", fetch="LAZY")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="brokerage_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $brokerage;

    /**
     * @var float
     */
    private $buyingPower;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var float
     */
    private $equity;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var float
     */
    private $portfolioValue;

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
     * @var ArrayCollection|Orders[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Order", mappedBy="account", fetch="LAZY")
     */
    private $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->positions = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * @return string [description]
     */
    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(string $accountNumber): Account
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    public function getAccountStatusType(): AccountStatusType
    {
        return $this->accountStatusType;
    }

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

    public function setApiSecret(string $apiSecret): Account
    {
        $this->apiSecret = $apiSecret;

        return $this;
    }

    public function getBrokerage(): Brokerage
    {
        return $this->brokerage;
    }

    public function setBrokerage(Brokerage $brokerage): Account
    {
        $this->brokerage = $brokerage;

        return $this;
    }

    public function getBuyingPower(): float
    {
        return $this->buying_power;
    }

    public function setBuyingPower(float $buyingPower): Account
    {
        $this->buyingPower = $buyingPower;

        return $this;
    }

    public function getCurrency(): float
    {
        return $this->currency;
    }

    public function setCurrency(float $currency): Account
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string $description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description = null): Account
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return float [description]
     */
    public function getEquity(): float
    {
        return $this->equity;
    }

    public function setEquity(float $equity): Account
    {
        $this->equity = $equity;

        return $this;
    }

    /**
     * @return string $name
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Account
    {
        $this->name = $name;

        return $this;
    }

    public function addUser(User $user): Account
    {
        $this->users->add($user);

        return $this;
    }

    /**
     * @return User[]|ArrayCollection|PersistentCollection $users
     */
    public function getUsers(): array
    {
        return $this->users;
    }

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

    public function addPosition(Position $position): Account
    {
        $this->positions->add($position);

        return $this;
    }

    /**
     * @return ArrayCollection|Position[]|PersistentCollection
     */
    public function getPositions(): array
    {
        return $this->positions;
    }

    /**
     * @param ArrayCollection $positions
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
    public function getOrders(): array
    {
        return $this->orders;
    }

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
}
