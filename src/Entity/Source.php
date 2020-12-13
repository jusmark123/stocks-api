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
 * class Source.
 *
 * @ORM\Table(
 * 		name="source",
 * 		uniqueConstraints={
 * 			@ORM\UniqueConstraint(name="source_un_guid", columns={"guid"})
 * 		}
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\SourceRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class Source extends AbstractGuidEntity
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
     * @var SourceType`
     *
     * @ORM\ManyToOne(targetEntity="SourceType", fetch="LAZY")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="source_type_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $sourceType;

    /**
     * @var ArrayCollection|Order[]|PersistentCollection
     * @ORM\OneToMany(targetEntity="Order", mappedBy="source", fetch="LAZY")
     */
    private $orders;

    /**
     * @var ArrayCollection|Job[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Job", mappedBy="source", fetch="LAZY")
     */
    private $jobs;

    /**
     * @var ArrayCollection|Position[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Position", mappedBy="source", fetch="LAZY")
     */
    private $positions;

    /**
     * Source constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->jobs = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->positions = new ArrayCollection();
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
     * @return $this
     */
    public function setName(string $name): Source
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

    /**
     * @param string|null $description
     *
     * @return $this
     */
    public function setDescription(string $description = null): Source
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return SourceType
     */
    public function getSourceType(): SourceType
    {
        return $this->sourceType;
    }

    /**
     * @param SourceType $sourceType
     *
     * @return $this
     */
    public function setSourceType(SourceType $sourceType): Source
    {
        $this->sourceType = $sourceType;

        return $this;
    }

    /**
     * @param Order $order
     *
     * @return $this
     */
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

    public function removeOrder(Order $order): Source
    {
        $this->orders->removeElement($order);

        return $this;
    }

    /**
     * @param ArrayCollection|Order[]|PersistentCollection $orders
     */
    public function setOrders(array $orders): Source
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
     * @return Source
     */
    public function setJobs($jobs): Source
    {
        $this->jobs = $jobs;

        return $this;
    }

    /**
     * @return Position[]|ArrayCollection|PersistentCollection
     */
    public function getPositions()
    {
        return $this->positions;
    }

    /**
     * @param Position[]|ArrayCollection|PersistentCollection $positions
     *
     * @return Source
     */
    public function setPositions($positions)
    {
        $this->positions = $positions;

        return $this;
    }
}
