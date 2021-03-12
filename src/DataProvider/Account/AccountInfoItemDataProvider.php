<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataProvider\Account;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\DTO\Brokerage\AccountInterface;
use App\Entity\Account;
use App\Entity\Manager\AccountEntityManager;
use App\Service\Entity\AccountEntityService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class AccountInfoItemDataProvider.
 */
class AccountInfoItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    const RESOURCE_CLASS = Account::class;
    const OPERATION_NAME = 'get_account_info';

    /**
     * @var AccountEntityService
     */
    private $accountService;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AccountInfoItemDataProvider constructor.
     *
     * @param AccountEntityService $accountService
     * @param AccountEntityManager $entityManager
     * @param LoggerInterface      $logger
     */
    public function __construct(
        AccountEntityService $accountService,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger)
    {
        $this->accountService = $accountService;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
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
     * @param string $resourceClass
     * @param  $id
     * @param string|null $operationName
     * @param array       $context
     *
     * @return AccountInterface
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): AccountInterface
    {
        $account = $this->getAccount($id);

        $accountSummary = $this->accountService->getAccountSummary($account);

        return $accountSummary->setAccount($account);
    }

    /**
     * @param string $id
     *
     * @return Account
     */
    private function getAccount(string $id): Account
    {
        $account = $this->entityManager->getRepository(Account::class)->findOneBy(['guid' => $id]);

        if (!$account instanceof Account) {
            throw new NotFoundHttpException(AccountEntityManager::ACCOUNT_NOT_FOUND);
        }

        return $account;
    }
}
