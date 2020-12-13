<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Client\BrokerageClient;
use App\DTO\Brokerage\OrderInfoInterface;
use App\Entity\Account;
use App\Entity\Job;
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
     * @param Job|null   $job
     *
     * @return array
     */
    public function getOrderHistory(Account $account, array $filters = [], ?Job $job = null): array
    {
        $brokerageService = $this->brokerageServiceProvider->getBrokerageService($account->getBrokerage());

        return $brokerageService->getOrderHistory($account, $filters, $job);
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
        $brokerageService = $this->brokerageServiceProvider->getBrokerageService($account->getBrokerage());

        $order = $this->orderEntityService
            ->getEntityManager()
            ->getRepository(Order::class)
            ->findOneBy([
               'brokerOrderId' => $orderInfo->getId(),
               'account' => $account,
            ]);

        if ($order instanceof Order) {
            return $order;
        }

        $orderInfo->setAccount($account);

        if (null === $orderInfo->getUser()) {
            $orderInfo->setUser($this->defaultTypeService->getDefaultUser());
        }

        $order = $brokerageService->createOrderFromOrderInfo($orderInfo);

        $this->orderEntityService->save($order);

        return $order;
    }
}
