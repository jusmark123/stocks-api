<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * class PositionSideType.
 *
 * @ORM\Table(
 * 		name="position_side_type",
 * 		uniqueConstraints={
 * 			@ORM\UniqueConstraint(name="position_side_type_un_name", columns={"name"})
 * 		}
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\PositionSideTypeRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class PositionSideType extends AbstractDefaultEntity
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
     * @var Brokerage
     *
     * @ORM\ManyToOne(targetEntity="Brokerage", inversedBy="positionSideTypes", fetch="LAZY")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="brokerage_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $brokerage;

    /**
     * @return string
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
    public function setName(string $name): PositionSideType
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(string $description): PositionSideType
    {
        $this->description = $description;

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
     * @return PositionSideType
     */
    public function setBrokerage(Brokerage $brokerage): PositionSideType
    {
        $this->brokerage = $brokerage;

        return $this;
    }
}
