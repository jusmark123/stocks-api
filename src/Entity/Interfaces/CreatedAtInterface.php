<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Interfaces;

interface CreatedAtInterface
{
    public function getCreatedAt(): \DateTime;

    /**
     * @return mixed
     */
    public function setCreatedAt(\DateTime $createdAt);
}
