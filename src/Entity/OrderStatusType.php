<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * class OrderStatusType.
 *
 * @ORM\Table(
 * 		name="order_status_type",
 * 		uniqueConstraints={
 * 			@ORM\UniqueConstraint(name="order_status_type_un_name", columns={"name"})
 * 		}
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\OrderStatusTypeRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class OrderStatusType extends AbstractDefaultEntity
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
     * @return string $name
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): OrderStatusType
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

    public function setDescription(string $description): OrderStatusType
    {
        $this->description = $description;

        return $this;
    }
}
