<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataProvider\Account;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Account;
use App\Entity\Manager\AccountEntityManager;
use App\Service\Brokerage\BrokerageServiceProvider;
use Psr\Log\LoggerInterface;

class AccountCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    const RESOURCE_CLASS = Account::class;
    const OPERATION_NAME = 'get';

    /**
     * @var AccountEntityManager
     */
    private $accountEntityManager;

    /**
     * @var BrokerageServiceProvider
     */
    private $brokerageServiceProvider;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AccountItemDataProvider constructor.
     *
     * @param AccountEntityManager     $accountEntityManager
     * @param BrokerageServiceProvider $brokerageServiceProvider
     * @param LoggerInterface          $logger
     */
    public function __construct(
        AccountEntityManager $accountEntityManager,
        BrokerageServiceProvider $brokerageServiceProvider,
        LoggerInterface $logger
    ) {
        $this->accountEntityManager = $accountEntityManager;
        $this->brokerageServiceProvider = $brokerageServiceProvider;
        $this->logger = $logger;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $accounts = $this->accountEntityManager->findAll();
        $accounts = array_map(function ($account) {
            $brokerageService = $this->brokerageServiceProvider->getBrokerageService($account->getBrokerage());
            $accountInfo = $brokerageService->getAccountInfo($account);
            $account->setAccountInfo($accountInfo);

            return $account;
        }, $accounts);

        return $accounts;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return self::RESOURCE_CLASS === $resourceClass && self::OPERATION_NAME === $operationName;
    }
}
