<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\DTO\Brokerage\BrokerageOrderInterface;
use App\DTO\SyncOrdersRequest;
use App\Entity\Account;
use App\Entity\Factory\OrderLogFactory;
use App\Entity\Job;
use App\Entity\Order;
use App\Entity\OrderStatusType;
use App\Entity\OrderType;
use App\Entity\TickerType;
use App\Helper\ValidationHelper;
use App\Service\Brokerage\BrokerageServiceProvider;
use App\Service\Ticker\TickerService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class OrderService.
 */
class OrderService extends AbstractService
{
    private BrokerageServiceProvider $brokerageServiceProvider;
    private DefaultTypeService $defaultTypeService;
    private EntityManagerInterface $entityManager;
    private PositionService $positionService;
    private TickerService $tickerService;
    private ValidationHelper $validator;

    /**
     * OrderService constructor.
     *
     * @param BrokerageServiceProvider $brokerServiceProvider
     * @param DefaultTypeService       $defaultTypeService
     * @param EntityManagerInterface   $entityManager
     * @param LoggerInterface          $logger
     * @param PositionService          $positionService
     * @param TickerService            $tickerService
     * @param ValidationHelper         $validator
     */
    public function __construct(
        BrokerageServiceProvider $brokerServiceProvider,
        DefaultTypeService $defaultTypeService,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        PositionService $positionService,
        TickerService $tickerService,
        ValidationHelper $validator
    ) {
        $this->brokerageServiceProvider = $brokerServiceProvider;
        $this->defaultTypeService = $defaultTypeService;
        $this->entityManager = $entityManager;
        $this->positionService = $positionService;
        $this->tickerService = $tickerService;
        $this->validator = $validator;

        parent::__construct($logger);
    }

    public function createOrderLog($order, $brokerOrder)
    {
        $orderLog = OrderLogFactory::create($order);
    }

    /**
     * @param SyncOrdersRequest   $request
     * @param MessageBusInterface $messageBus
     * @param Job|null            $job
     *
     * @throws \Exception
     *
     * @return Job|null
     */
    public function fetchOrderHistory(SyncOrdersRequest $request, MessageBusInterface $messageBus, Job $job): ?Job
    {
        try {
            $brokerageService = $this->brokerageServiceProvider
                ->getBrokerageService($request->getAccount()->getBrokerage());

            return $brokerageService->fetchOrderHistory($request, $messageBus, $job);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getBrokerageUniqueOrderKey(Account $account)
    {
        $brokerageService = $this->brokerageServiceProvider->getBrokerageService($account->getBrokerage());

        $constantsClass = $brokerageService->getConstantsClass();

        return $constantsClass::ORDER_INFO_UNIQUE_KEY;
    }

    public function getOrderStatusForOrder(Order $order, string $status)
    {
        /** @var OrderStatusType $orderStatus */
        $orderStatus = $this->entityManager->getRepository(OrderStatusType::class);
        $order->setOrderStatus($orderStatus);
    }

    public function getOrderTypeForOrder(Order $order, string $type)
    {
        /** @var OrderType $orderType */
        $orderType = $this->entityManager->getRepository(TickerType::class)->findOneBy(['name' => $type]);
        $order->setOrderType($orderType);
    }

    public function prepareOrder(Order $order, BrokerageOrderInterface $brokerOrder)
    {
        $this->getOrderTypeForOrder($order, $brokerOrder->getType());
        $this->getOrderStatusForOrder($order, $brokerOrder->getStatus());
        $this->createOrderLog($order, $brokerOrder);
        $this->tickerService->getTickerForOrder($order, $brokerOrder->getSymbol());
        $this->positionService->getPositionForOrder($order, $brokerOrder);
    }

    /**
     * @param array $orderInfoMessage
     * @param Job   $job
     *
     * @return BrokerageOrderInterface|null
     */
    public function syncOrderHistory(array $orderInfoMessage, Job $job): ?Order
    {
        $account = $job->getAccount();
        $source = $job->getSource();

        $brokerageService = $this->brokerageServiceProvider->getBrokerageService($account->getBrokerage());

        $orderInfo = $brokerageService
            ->createOrderInfoFromMessage($orderInfoMessage)
            ->setAccount($account)
            ->setSource($source);

        if (null === $orderInfo->getUser()) {
            $orderInfo->setUser($this->defaultTypeService->getDefaultUser());
        }

        $order = $brokerageService->createOrderFromOrderInfo($orderInfo, $job);

        $this->orderEntityService->save($order);

        return $order;
    }
}
