<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * class Source.
 *
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"algorithm"="Algorithm", "user"="User"})
 */
abstract class Source extends AbstractEntity
{
    /**
     * @var ArrayCollection|Job[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Job", mappedBy="source", fetch="LAZY")
     */
    private $jobs;

    /**
     * @var ArrayCollection|Order[]|PersistentCollection
     * @ORM\OneToMany(targetEntity="Order", mappedBy="source", fetch="LAZY")
     */
    private $orders;

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
        $this->jobs = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->positions = new ArrayCollection();
    }

    /**
     * @return Job[]|ArrayCollection|PersistentCollection
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    /**
     * @param Job[]|ArrayCollection|PersistentCollection $jobs
     *
     * @return self
     */
    public function setJobs($jobs): self
    {
        $this->jobs = $jobs;

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
     * @return self
     */
    public function setOrders($orders): self
    {
        $this->orders = $orders;

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
     * @return self
     */
    public function setPositions($positions): self
    {
        $this->positions = $positions;

        return $this;
    }
}
