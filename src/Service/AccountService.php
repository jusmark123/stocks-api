<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Client\BrokerageClient;
use App\DTO\Brokerage\Interfaces\AccountInfoInterface;
use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Account;
use App\Entity\Manager\AccountEntityManager;
use App\Helper\ValidationHelper;
use App\Service\Brokerage\BrokerageServiceAwareTrait;
use Psr\Log\LoggerInterface;

class AccountService extends AbstractService
{
    use BrokerageServiceAwareTrait;

    /**
     * @var AccountEntityManager
     */
    private $entityManager;

    /**
     * @var BrokerageClient
     */
    private $brokerageClient;

    /**
     * AccountService constructor.
     *
     * @param AccountEntityManager $entityManager
     * @param BrokerageClient      $brokerageClient
     * @param iterable             $brokerageServices
     * @param LoggerInterface      $logger
     * @param ValidationHelper     $validator
     */
    public function __construct(
        AccountEntityManager $entityManager,
        BrokerageClient $brokerageClient,
        iterable $brokerageServices,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        $this->entityManager = $accountEntityManager;
        $this->brokerageClient = $brokerageClient;
        $this->brokerageServices = $brokerageServices;
        parent::__construct($entityManager, $validator, $logger);
    }

    /**
     * @param Account $account
     *
     * @return AccountInfoInterface
     */
    public function getAccountInfo(Account $account): AccountInfoInterface
    {
        $brokerageService = $this->getBrokerageService($account->getBrokerage());

        return $brokerageService->getAccountInfo($account);
    }

    /**
     * @param Account    $account
     * @param array|null $filters
     *
     * @return array
     */
    public function getOrderHistory(Account $account, array $filters = []): array
    {
        $brokerageService = $this->getBrokerageService($account->getBrokerage());

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
        $brokerageService = $this->getBrokerageService($account->getBrokerage());

        $orderInfo = $brokerageService->createOrderInfoFromMessage($orderInfoMessage);

        $this->validator->validate($orderInfo);

        return $orderInfo;
    }
}
