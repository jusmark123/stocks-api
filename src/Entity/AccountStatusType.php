<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * class AccountStatusType.
 *
 * @ORM\Table(
 * 		name="account_status_type",
 * 		uniqueConstraints={
 * 			@ORM\UniqueConstraint(name="account_status_type_un_name", columns={"name"})
 * 		}
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\AccountStatusTypeRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class AccountStatusType extends AbstractDefaultEntity
{
    public const ACTIVE = 'active';
    public const INACTIVE = 'inactive';

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
     * @return string $name
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): AccountStatusType
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string $description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): AccountStatusType
    {
        $this->description = $description;

        return $this;
    }
}
