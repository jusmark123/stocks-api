<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataProvider\Account;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Account;
use App\Entity\Manager\AccountEntityManager;
use App\Service\Brokerage\BrokerageServiceProvider;

/**
 * Class AccountItemDataProvider.
 */
class AccountItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
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

    /**
     * @param string      $resourceClass
     * @param string|null $operationName
     * @param array       $context
     *
     * @return bool
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return self::RESOURCE_CLASS === $resourceClass
            && self::OPERATION_NAME === $operationName;
    }

    /**
     * @param string           $resourceClass
     * @param array|int|string $id
     * @param string|null      $operationName
     * @param array            $context
     *
     * @return object|null
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?object
    {
        $account = $this->accountEntityManager->findOneBy(['guid' => $id]);
        $brokerageService = $this->brokerageServiceProvider->getBrokerageService($account->getBrokerage());
        $accountInfo = $brokerageService->getAccountInfo($account);
        $account->setAccountInfo($accountInfo);

        return $account;
    }
}
