<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Entity\Manager\OrderStatusTypeEntityManager;

class DefaultTypeService extends AbstractService
{
    /**
     * @var OrderStatusTypeEntityManager
     */
    private $orderStatusType;

    public function __construct(
        OrderStatusTypeEntityManager $orderStatusType
    ) {
        $this->orderStatusType = $orderStatusType;
    }
}
