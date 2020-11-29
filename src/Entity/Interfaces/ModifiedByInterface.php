<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Interfaces;

interface ModifiedByInterface
{
    /**
     * @return \DateTime
     */
    public function getModifiedBy(): ?string;

    /**
     * @return mixed
     */
    public function setModifiedBy(string $createdBy);
}
