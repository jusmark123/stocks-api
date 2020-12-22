<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataProvider\Account;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\DTO\Brokerage\Interfaces\AccountInfoInterface;
use App\Entity\Account;
use App\Entity\Manager\AccountEntityManager;
use App\Service\AccountService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AccountInfoItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    const RESOURCE_CLASS = Account::class;
    const OPERATION_NAME = 'get_account_info';

    /**
     * @var AccountService
     */
    private $accountService;

    /**
     * @var AccountEntityManager
     */
    private $accountEntityManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AccountDataProvider Constructoru.
     */
    public function __construct(
            AccountService $accountService,
            AccountEntityManager $accountEntityManager,
            LoggerInterface $logger)
    {
        $this->accountService = $accountService;
        $this->accountEntityManager = $accountEntityManager;
        $this->logger = $logger;
    }

    /**
     * @param string $resourceClass
     * @param string $operationName
     * @param array  $context
     *
     * @return bool
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return self::RESOURCE_CLASS === $resourceClass && self::OPERATION_NAME === $operationName;
    }

    /**
     * @param string $resourceClass
     * @param  $id
     * @param string $operationName
     * @param array  $context
     *
     * @return AccountInfoInterface
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): AccountInfoInterface
    {
        $account = $this->getAccount($id->toString());

        $accountInfo = $this->accountService->getAccountInfo($account);

        return $accountInfo->setAccount($account);
    }

    /**
     * @param string $id
     *
     * @return Account
     */
    private function getAccount(string $id): Account
    {
        $account = $this->accountEntityManager->findOneBy(['guid' => $id]);

        if (!$account instanceof Account) {
            throw new NotFoundHttpException(AccountEntityManager::ACCOUNT_NOT_FOUND);
        }

        return $account;
    }
}
