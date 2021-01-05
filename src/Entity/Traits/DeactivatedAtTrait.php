<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait DeactivatedAtTrait
{
    /**
     * @var \DateTime|null
     * @ORM\Column(name="deactivated_at", type="datetime", nullable=true)
     */
    protected $deactivatedAt;

    /**
     * Sets deactivatedAt.
     *
     * @param \DateTime $deactivatedAt|null
     *
     * @return $this
     */
    public function setDeactivatedAt(?\DateTime $deactivatedAt = null)
    {
        $this->deactivatedAt = $deactivatedAt;

        return $this;
    }

    /**
     * Returns deactivatedAt.
     *
     * @return \DateTime deactivatedAt
     */
    public function getDeactivatedAt(): ?\DateTime
    {
        return $this->deactivatedAt;
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
