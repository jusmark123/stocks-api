<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Interfaces;

interface EntityGuidInterface
{
    /**
     * @return \Ramsey\Uuid\Uuid
     */
    public function getGuid(): \Ramsey\Uuid\Uuid;

    /**
     * @param \Ramsey\Uuid\Uuid $uuid
     *
     * @return EntityGuidInterface
     */
    public function setGuid(\Ramsey\Uuid\Uuid $uuid): EntityGuidInterface;
}
