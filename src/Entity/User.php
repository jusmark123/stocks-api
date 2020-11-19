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
 * 			@ORM\UniqueConstraint(name="user_un_guid", columns={"guid"})
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
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
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
     * @ORM\Column(name="phone", type="string", length=10, nullable=false)
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
     * @ORM\ManyToMany(targetEntity="Account", mappedBy="users", fetch="LAZY")
     */
    private $accounts;

    /**
     * @var ArrayCollection|Order[]|PersistentCollection
     * @ORM\OneToMany(targetEntity="Order", mappedBy="user", fetch="LAZY")
     */
    private $orders;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    /**
     * @return string $name
     */
    public function getUserName(): string
    {
        return $this->name;
    }

    public function setUserName(string $name): User
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string $description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

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

    public function setLastName(string $lastName): User
    {
        $this->lastName = $lastName;

        return $this;
    }

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

    public function removeAccount(Account $account): User
    {
        $this->accounts->removeElement($account);

        return $this;
    }

    public function setAccounts(array $accounts): User
    {
        $this->accounts = $array;

        return $this;
    }

    public function addOrder(Order $order): User
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getOrders(): array
    {
        return $this->orders->getValues();
    }

    public function removeOrder(Order $order): User
    {
        $this->orders->removeElement($order);

        return $this;
    }

    /**
     * @param ArrayCollection $orders
     */
    public function setOrders(array $orders): User
    {
        $this->orders = $orders;

        return $this;
    }
}
