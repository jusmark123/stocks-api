<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Entity;

use App\Entity\Factory\OrderFactory;
use App\Entity\Manager\OrderEntityManager;

class OrderEntityService extends AbstractService
{
    /** @var OrderService */
    protected $orderService;

    /** @var OrderEntityManager */
    protected $entityManager;

    /** @var OrderStatusEntityService */
    protected $orderStatusEntityService;

    /** @var OrderTypeEntityService */
    protected $orderTypeEntityService;

    public function __construct(
         OrderEntityManager $entityManager,
         OrderFactory $factory,
         OrderStatusEntityService $orderStatusEntityService,
         OrderTypeEntityService $orderTypeEntityService,
         OrderService $orderService
        ) {
        $this->orderService = $orderService;

        parent::__construct($entityManager, $factory);
    }

    public function createOrderFromOrderInfo(OrderInfoInterface $orderInfo)
    {
    }
}
