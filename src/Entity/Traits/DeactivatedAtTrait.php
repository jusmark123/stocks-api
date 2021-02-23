<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait DeactivatedAtTrait.
 */
trait DeactivatedAtTrait
{
    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="deactivated_at", type="datetime", nullable=true)
     */
    protected ?DateTime $deactivatedAt = null;

    /**
     * @var string|null
     *
     * @ORM\Column(name="deactivated_by", type="string", nullable=true)
     */
    protected ?string $deactivatedBy = null;

    /**
     * Sets deactivatedAt.
     *
     * @param DateTime|null $deactivatedAt
     *
     * @return $this
     */
    public function setDeactivatedAt(?DateTime $deactivatedAt = null): self
    {
        $this->deactivatedAt = $deactivatedAt;

        return $this;
    }

    /**
     * Returns deactivatedAt.
     *
     * @return DateTime deactivatedAt
     */
    public function getDeactivatedAt(): ?DateTime
    {
        return $this->deactivatedAt;
    }

    /**
     * @param string|null $deactivatedBy
     *
     * @return $this
     */
    public function setDeactivatedBy(?string $deactivatedBy = null): self
    {
        $this->deactivatedBy = $deactivatedBy;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDeactivatedBy(): ?string
    {
        return $this->deactivatedBy;
    }

    /**
     * Is deactivatedAt.
     *
     * @return bool deactivatedAt
     */
    public function isDeactivated()
    {
        return null !== $this->deactivatedAt;
    }
}
