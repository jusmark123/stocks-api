<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Client\BrokerageClient;
use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Manager\OrderEntityManager;
use App\Entity\Order;
use App\Helper\ValidationHelper;
use App\Service\Brokerage\BrokerageServiceAwareTrait;
use Psr\Log\LoggerInterface;

/**
 * Class OrderService.
 */
class OrderService extends AbstractService
{
    use BrokerageServiceAwareTrait;

    /**
     * @var OrderEntityManager
     */
    private $brokerageClient;

    /**
     * @var OrderEntityManager
     */
    private $entityManager;

    /**
     * OrderService constructor.
     *
     * @param BrokerageClient    $brokerageClient
     * @param iterable           $brokerageServices
     * @param LoggerInterface    $logger
     * @param OrderEntityManager $entityManager
     * @param ValidationHelper   $validator
     */
    public function __construct(
        BrokerageClient $brokerageClient,
        iterable $brokerageServices,
        LoggerInterface $logger,
        OrderEntityManager $entityManager,
        ValidationHelper $validator
    ) {
        $this->brokerageClient = $brokerageClient;
        $this->brokerageServices = $brokerageServices;
        parent::__construct($entityManager, $validator, $logger);
    }

    /**
     * @param OrderInfoInterface $orderInfo
     *
     * @return Order
     */
    public function createOrderFromOrderInfo(OrderInfoInterface $orderInfo)
    {
        $account = $orderInfo->getAccount();

        $brokerageService = $this->getBrokerageService($account->getBrokerage());

        $order = $brokerageService->createOrderFromOrderInfo($orderInfo);

        $this->validator->validate($order);

        return $order;
    }
}
