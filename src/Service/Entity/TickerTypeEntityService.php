<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Entity;

use App\Entity\TickerType;

class TickerTypeEntityService extends AbstractEntityService
{
    /**
     * @return mixed
     */
    public function getTickerTypes()
    {
        return $this->entityManager
            ->getRepository(TickerType::class)
            ->findAll();
    }
}
