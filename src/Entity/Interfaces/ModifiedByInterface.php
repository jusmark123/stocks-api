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
    public function getCreatedBy(): string;

    /**
     * @return mixed
     */
    public function setCreatedBy(string $createdBy);
}
