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
     * @var string|null
     *
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
     * @param string|null $deactivatedBy
     *
     * @return $this
     */
    public function setDeactivatedBy(?string $deactivatedBy = null)
    {
        $this->deactivatedBy = $deactivatedBy;

        return $this;
    }
}
