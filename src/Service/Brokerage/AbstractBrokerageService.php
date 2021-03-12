<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Constants\Brokerage\PolygonContstants;
use App\DTO\Brokerage\AccountHistoryInterface;
use App\DTO\Brokerage\AccountHistoryRequestInterface;
use App\DTO\Brokerage\AccountInterface;
use App\DTO\Brokerage\Alpaca\AlpacaAccountConfiguration;
use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Job;
use App\Exception\InvalidAccountConfiguration;
use App\Helper\ValidationHelper;
use App\Service\JobService;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

/**
 * Class AbstractBrokerageService.
 */
abstract class AbstractBrokerageService implements BrokerageServiceInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected const BROKERAGE_CONSTANTS = '';

    /**
     * @var Client
     */
    protected Client $cache;

    /**
     * @var Account
     */
    protected Account $defaultAccount;

    /**
     * @var JobService
     */
    protected JobService $jobService;

    /**
     * @var ValidationHelper
     */
    protected ValidationHelper $validator;

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;

    /**
     * AbstractBrokerageService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param JobService             $jobService
     * @param LoggerInterface        $logger
     * @param ValidationHelper       $validator
     */
    public function __construct(
        Client $cache,
        EntityManagerInterface $entityManager,
        JobService $jobService,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        $this->cache = $cache;
        $this->entityManager = $entityManager;
        $this->jobService = $jobService;
        $this->logger = $logger;
        $this->validator = $validator;
    }

    /**
     * @return Account
     */
    public function getDefaultAccount()
    {
        if (null === $this->defaultAccount) {
            $this->setDefaultAccount(PolygonContstants::BROKERAGE_NAME);
        }

        return $this->defaultAccount;
    }

    /**
     * @param string $brokerageName
     */
    public function setDefaultAccount(string $brokerageName)
    {
        $brokerage = $this->entityManager
            ->getRepository(Brokerage::class)
            ->findOneBy(['name' => $brokerageName]);

        $this->defaultAccount = $brokerage->getDefaultAccount();
    }

    /**
     * @param string       $uri
     * @param Account|null $account
     * @param array|null   $params
     *
     * @return string
     */
    protected function getUri(string $uri, ?Account $account = null, ?array $params = null): string
    {
        $brokerage = $account->getBrokerage();

        if (!$brokerage->hasPaperAccounts() && $account->isPaperAccount()) {
            throw new InvalidAccountConfiguration('The brokerage does not support paper trading accounts');
        }

        if ($account->isPaperAccount()) {
            $uri = $this->getConstantsClass()::PAPER_API_ENDPOINT.$uri;
        } else {
            $uri = $this->getConstantsClass()::API_ENDPOINT.$uri;
        }

        if (null !== $params) {
            $uri .= '&'.http_build_query($params);
        }

        return $uri;
    }

    /**
     * @param Brokerage $brokerage
     *
     * @return bool
     */
    public function supports(Brokerage $brokerage): bool
    {
        return $brokerage instanceof Brokerage
            && $this->getConstantsClass()::BROKERAGE_NAME === $brokerage->getName();
    }

    /**
     * @param Account $account
     *
     * @return AlpacaAccountConfiguration|null
     */
    public function fetchAccountConfiguration(Account $account): ?AlpacaAccountConfiguration
    {
        // TODO: Implement fetchAccountConfiguration() method.
    }

    /**
     * @param AccountHistoryRequestInterface $request
     *
     * @return AccountHistoryInterface|null
     */
    public function fetchAccountHistory(AccountHistoryRequestInterface $request): ?AccountHistoryInterface
    {
        // TODO: Implement fetchAccountHistory() method.
    }

    /**
     * @param Account $account
     *
     * @return AccountInterface|null
     */
    public function fetchAccountInfo(Account $account): ?AccountInterface
    {
        // TODO: Implement getAccountSummary() method.
    }

    /**
     * @param Account $account
     *
     * @return Job|null
     */
    public function fetchOrders(Account $account): ?array
    {
        // TODO: Implement fetchOrderHistory() method.
    }

    /**
     * @param $account
     *
     * @return array
     */
    public function fetchPositions(Account $account): ?array
    {
        // TODO: Implement fetchPositions() method.
    }

    /**
     * @return string
     */
    public function getConstantsClass(): string
    {
        return static::BROKERAGE_CONSTANTS;
    }
}
