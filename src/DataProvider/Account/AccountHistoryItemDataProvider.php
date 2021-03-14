<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataProvider\Account;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\DTO\Brokerage\Factory\AccountHistoryRequestFactory;
use App\Entity\Account;
use App\Entity\Manager\AccountEntityManager;
use App\Service\Brokerage\BrokerageServiceProvider;

/**
 * Class AccountHistoryItemDataProvider.
 */
class AccountHistoryItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    const RESOURCE_CLASS = Account::class;
    const OPERATION_NAME = 'account_history';

    private AccountEntityManager $accountManager;

    private BrokerageServiceProvider $brokerageProvider;

    public function __construct(
        AccountEntityManager $accountManager,
        BrokerageServiceProvider $brokerageProvider
    ) {
        $this->accountManager = $accountManager;
        $this->brokerageProvider = $brokerageProvider;
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
        return self::RESOURCE_CLASS === $resourceClass && self::OPERATION_NAME === $operationName;
    }

    /**
     * @param string           $resourceClass
     * @param array|int|string $id
     * @param string|null      $operationName
     * @param array            $context
     *
     * @return object|void|null
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        $account = $this->accountManager->findOneBy(['guid' => $id]);
        $filters = $context['filters'] ?? [];
        $request = AccountHistoryRequestFactory::create($account, $filters);
        $brokerageService = $this->brokerageProvider->getBrokerageService($account);

        return $brokerageService->fetchAccountHistory($request);
    }
}
