<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Interfaces;

interface CreatedByInterface
{
    /*
     * @return string
     */
    public function getCreatedBy(): string;

    /**
     * @param string $createdBy
     *
     * @return self
     */
    public function setCreatedBy(string $createdBy);
}
