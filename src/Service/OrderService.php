<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Factory\OrderFactory;
use App\Entity\Manager\OrderEntityManager;
use App\Entity\Order;
use App\Helper\ValidationHelper;
use Psr\Log\LoggerInterface;

/**
 * Class OrderService.
 */
class OrderService extends AbstractService
{
    /**
     * @var OrderEntityManager
     */
    private $entityManager;

    /**
     * OrderService constructor.
     *
     * @param OrderFactory $orderFactory
     */
    public function __construct(
        LoggerInterface $logger,
        OrderEntityManager $entityManager,
        ValidationHelper $validator
    ) {
        $this->entityManager = $entityManager;
        $this->setLogger($logger);
        $this->setValidator($validator);
    }

    /**
     * @param OrderInfoInterface $orderInfo
     *
     * @return Order
     */
    public function createOrderFromOrderInfo(OrderInfoInterface $orderInfo)
    {
        $order = OrderFactory::create();

        try {
            $this->validator->validate($orderInfo);

            $order = $order->setAccount($orderInfo->getAccount());

            $this->validator->validate($order);

            return $order;
        } catch (\Exception $e) {
            $this->logger->info();
        }
    }

    /**
     * @param Order $order
     */
    public function save(Order $order)
    {
        $this->entityManager->persist($order);
    }
}
