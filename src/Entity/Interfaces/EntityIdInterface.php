<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Interfaces;

/**
 * Class EntityIdInterface.
 */
interface EntityIdInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id);
}
