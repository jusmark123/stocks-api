<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Client\BrokerageClient;
use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Account;
use App\Entity\Order;
use App\Helper\ValidationHelper;
use App\Service\Brokerage\BrokerageServiceProvider;
use App\Service\Entity\OrderEntityService;
use Psr\Log\LoggerInterface;

/**
 * Class OrderService.
 */
class OrderService extends AbstractService
{
    /**
     * @var BrokerageClient
     */
    private $brokerageClient;

    /**
     * @var BrokerageServiceProvider
     */
    private $brokerageServiceProvider;

    /**
     * @var DefaultTypeService
     */
    private $defaultTypeService;

    /**
     * @var OrderEntityService
     */
    private $orderEntityService;

    /**
     * @var ValidationHelper
     */
    private $validator;

    /**
     * OrderService constructor.
     *
     * @param BrokerageClient          $brokerClient
     * @param BrokerageServiceProvider $brokerServiceProvider
     * @param LoggerInterface          $logger
     * @param OrderEntityService       $orderEntityService
     * @param ValidationHelper         $validator
     */
    public function __construct(
        BrokerageClient $brokerClient,
        BrokerageServiceProvider $brokerServiceProvider,
        DefaultTypeService $defaultTypeService,
        LoggerInterface $logger,
        OrderEntityService $orderEntityService,
        ValidationHelper $validator
    ) {
        $this->brokerageClient = $brokerClient;
        $this->brokerageServiceProvider = $brokerServiceProvider;
        $this->defaultTypeService = $defaultTypeService;
        $this->orderEntityService = $orderEntityService;
        $this->validator = $validator;
        parent::__construct($logger);
    }

    /**
     * @param Account    $account
     * @param array|null $filters
     *
     * @return array
     */
    public function getOrderHistory(Account $account, array $filters = []): array
    {
        $brokerageService = $this->brokerageServiceProvider->getBrokerageService($account->getBrokerage());

        return $brokerageService->getOrderHistory($account, $filters);
    }

    /**
     * @param Account $account
     * @param array   $orderInfoMessage
     *
     * @return OrderInfoInterface|null
     */
    public function createOrderInfoFromMessage(Account $account, array $orderInfoMessage): ?OrderInfoInterface
    {
        $brokerageService = $this->brokerageServiceProvider->getBrokerageService($account->getBrokerage());

        return $brokerageService->createOrderInfoFromMessage($orderInfoMessage);
    }

    /**
     * @param Account            $account
     * @param OrderInfoInterface $orderInfo
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return Order|null
     */
    public function createOrderFromOrderInfo(Account $account, OrderInfoInterface $orderInfo): Order
    {
        $this->validator->validate($orderInfo);

        $brokerageService = $this->brokerageServiceProvider->getBrokerageService($account->getBrokerage());

        $orderInfo->setAccount($account);

        if (null === $orderInfo->getUser()) {
            $orderInfo->setUser($this->defaultTypeService->getDefaultUser());
        }

        $order = $brokerageService->createOrderFromOrderInfo($orderInfo);

        $this->orderEntityService->save($order);

        return $order;
    }
}
