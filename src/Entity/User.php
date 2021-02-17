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
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

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
class User extends AbstractGuidEntity implements UserInterface
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
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string|null
     * @Groups({"user.put"})
     */
    private $plainPassword;

    /**
     * @var string|null
     * @ORM\Column(name="phone", type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @var string|null
     * @ORM\Column(name="avatar", type="text", length=1677216, nullable=true)
     */
    private $avatar;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var UserType
     *
     * @ORM\ManyToOne(targetEntity="UserType", fetch="LAZY")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="user_type_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $userType;

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
     * @var array
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * User constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
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
     * @return UserType
     */
    public function getUserType(): UserType
    {
        return $this->userType;
    }

    /**
     * @param UserType $userType
     *
     * @return User
     */
    public function setUserType(UserType $userType): User
    {
        $this->userType = $userType;

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
        $this->accounts = $accounts;

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
        return $this->orders;
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
     *
     * @return User
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

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar(string $avatar): User
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return string[] The user roles
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string|null The encoded password if any
     */
    public function getPassword(): ?string
    {
        return (string) $this->password;
    }

    /**
     * @param string|null $password
     *
     * @return $this
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returns the plain-text password.
     *
     * This field is not in the database, and this information is not persisted.
     * This field acts as an intermediary when users want to change their passwords.
     * When a value is entered in this field, it will be encrypted and stored in
     * the password field instead.
     *
     * @return string|null The plaintext password if any
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * Sets the plain-text password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPlainPassword(string $password): User
    {
        $this->plainPassword = $password;

        return $this;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * Return the user object as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getGuid()->toString(),
            'username' => $this->getUsername(),
            'email' => $this->getEmail(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'phone' => $this->getPhone(),
            'description' => $this->getDescription(),
            'roles' => $this->getRoles(),
            'avatar' => $this->getAvatar(),
        ];
    }

    /**
     * Serialize the user object.
     *
     * @return array
     */
    public function __serialize(): array
    {
        return $this->toArray();
    }
}
