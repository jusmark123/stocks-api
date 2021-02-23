<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Interfaces;

use DateTime;

interface ModifiedAtInterface
{
    /**
     * @return mixed
     */
    public function getModifiedAt(): DateTime;

    /**
     * @param DateTime $modifiedAt
     *
     * @return mixed
     */
    public function setModifiedAt(DateTime $modifiedAt): self;

    /**
     * @return DateTime
     */
    public function getModifiedBy(): ?string;

    /**
     * @param string $createdBy
     *
     * @return mixed
     */
    public function setModifiedBy(string $createdBy): self;
}
