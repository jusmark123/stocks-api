<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Interfaces;

use DateTime;

/**
 * Interface CreatedAtInterface.
 */
interface CreatedAtInterface
{
    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime;

    /**
     * @return string
     */
    public function getCreatedBy(): ?string;

    /**
     * @param DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(DateTime $createdAt): self;

    /**
     * @param string|null $createdBy
     *
     * @return $this
     */
    public function setCreatedBy(?string $createdBy): self;
}
