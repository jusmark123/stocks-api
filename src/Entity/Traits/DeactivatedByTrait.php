<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeactivatedByTrait.
 */
trait DeactivatedByTrait
{
    /**
     * @var \DateTime|null
     * @ORM\Column(name="deactivated_by", type="string", nullable=true)
     */
    protected $deactivatedBy;

    /**
     * @return string|null
     */
    public function getDeactivatedBy(): ?string
    {
        return $this->deactivatedBy;
    }

    /**
     * @return $this
     */
    public function setDeactivatedBy(string $deactivatedBy)
    {
        $this->deactivatedBy = $deactivatedBy;

        return $this;
    }
}
