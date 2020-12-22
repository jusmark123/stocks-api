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
     * AccountItemDataProvider constructor.
     *
     * @param AccountEntityManager     $accountEntityManager
     * @param BrokerageServiceProvider $brokerageServiceProvider
     */
    public function __construct(
        AccountEntityManager $accountEntityManager,
        BrokerageServiceProvider $brokerageServiceProvider
    ) {
        $this->accountEntityManager = $accountEntityManager;
        $this->brokerageServiceProvider = $brokerageServiceProvider;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $accounts = $this->accountEntityManager->findAll();
        $accounts = array_map(function ($account) {
            $this->brokerageServiceProvider->getBrokerageService($account->getBrokerage());
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
