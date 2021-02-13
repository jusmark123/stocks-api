<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\DTO\Brokerage\OrderInfoInterface;
use App\DTO\SyncOrdersRequest;
use App\Entity\Account;
use App\Entity\Job;
use App\Entity\Order;
use App\Helper\ValidationHelper;
use App\Service\Brokerage\BrokerageServiceProvider;
use App\Service\Entity\OrderEntityService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class OrderService.
 */
class OrderService extends AbstractService
{
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
     * @param BrokerageServiceProvider $brokerServiceProvider
     * @param DefaultTypeService       $defaultTypeService
     * @param LoggerInterface          $logger
     * @param OrderEntityService       $orderEntityService
     * @param ValidationHelper         $validator
     */
    public function __construct(
        BrokerageServiceProvider $brokerServiceProvider,
        DefaultTypeService $defaultTypeService,
        LoggerInterface $logger,
        OrderEntityService $orderEntityService,
        ValidationHelper $validator
    ) {
        $this->brokerageServiceProvider = $brokerServiceProvider;
        $this->defaultTypeService = $defaultTypeService;
        $this->orderEntityService = $orderEntityService;
        $this->validator = $validator;
        parent::__construct($logger);
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

    /**
     * @param array $orderInfoMessage
     * @param Job   $job
     *
     * @return OrderInfoInterface|null
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
