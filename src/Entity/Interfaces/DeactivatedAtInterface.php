<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Interfaces;

use DateTime;

/**
 * Interface DeactivatedAtInterface.
 */
interface DeactivatedAtInterface
{
    /**
     * @param DateTime|null $deactivatedAt
     *
     * @return mixed
     */
    public function setDeactivatedAt(?DateTime $deactivatedAt = null);

    /**
     * @return DateTime|null
     */
    public function getDeactivatedAt(): ?DateTime;
}
