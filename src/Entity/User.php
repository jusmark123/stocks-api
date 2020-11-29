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
 * class User.
 *
 * @ORM\Table(
 * 		name="user",
 * 		uniqueConstraints={
 * 			@ORM\UniqueConstraint(name="user_un_guid", columns={"guid"}),
 * 			@ORM\UniqueConstraint(name="user_un_email_username", columns={"email", "username"})
 * 		}
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class User extends AbstractGuidEntity
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="username", type="string", length=100, nullable=false)
     */
    private $username;

    /**
     * @var string|null
     * @ORM\Column(name="first_name", type="string", length=100, nullable=true)
     */
    private $firstName;

    /**
     * @var string|null
     * @ORM\Column(name="last_name", type="string", length=100, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string|null
     * @ORM\Column(name="phone", type="string", length=10, nullable=true)
     */
    private $phone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var ArrayCollection|Account[]|PersistentCollection
     * @ORM\ManyToMany(targetEntity="Account", mappedBy="users", fetch="LAZY", cascade={"persist"})
     */
    private $accounts;

    /**
     * @var ArrayCollection|Order[]|PersistentCollection
     * @ORM\OneToMany(targetEntity="Order", mappedBy="user", fetch="LAZY")
     */
    private $orders;

    /**
     * @var ArrayCollection|Job[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Job", mappedBy="user", fetch="LAZY")
     */
    private $jobs;

    /**
     * User Constructor.
     */
    public function __construct()
    {
        $this->accounts = new ArrayCollection();
        $this->jobs = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    /**
     * @return string $username
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;

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
     * @param string $description
     *
     * @return User
     */
    public function setDescription(string $description): User
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string $email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string $phone
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return User
     */
    public function setPhone(string $phone): User
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string $firstName
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName(string $firstName): User
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string $lastName
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName(string $lastName): User
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @param Account $account
     *
     * @return User
     */
    public function addAccount(Account $account): User
    {
        $this->accounts->add($account);

        return $this;
    }

    /**
     * @return ArrayCollection|Account[]|PersistentCollection
     */
    public function getAccounts(): array
    {
        return $this->accounts->getValues();
    }

    /**
     * @param Account $account
     *
     * @return User
     */
    public function removeAccount(Account $account): User
    {
        $this->accounts->removeElement($account);

        return $this;
    }

    /**
     * @param  ArrayCollection|Account[]|PersistentCollection
     *
     * @return User
     */
    public function setAccounts($accounts): User
    {
        $this->accounts = $array;

        return $this;
    }

    /**
     * @param Order $order
     *
     * @return User
     */
    public function addOrder(Order $order): User
    {
        $this->orders[] = $order;

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
     * @param Order $order
     *
     * @return User
     */
    public function removeOrder(Order $order): User
    {
        $this->orders->removeElement($order);

        return $this;
    }

    /**
     * @param ArrayCollection|Order[]|PersistentCollection $orders
     */
    public function setOrders($orders): User
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
     * @return User
     */
    public function setJobs($jobs): User
    {
        $this->jobs = $jobs;

        return $this;
    }
}
