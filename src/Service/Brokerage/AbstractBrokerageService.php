<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Constants\Brokerage\PolygonContstants;
use App\DTO\Brokerage\AccountInfoInterface;
use App\DTO\Brokerage\OrderInfoInterface;
use App\DTO\Brokerage\TickerInterface;
use App\DTO\SyncOrdersRequest;
use App\DTO\SyncTickersRequest;
use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Job;
use App\Entity\Order;
use App\Entity\Ticker;
use App\Exception\InvalidAccountConfiguration;
use App\Helper\ValidationHelper;
use App\Service\JobService;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class AbstractBrokerageService.
 */
abstract class AbstractBrokerageService implements BrokerageServiceInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Client
     */
    protected $cache;

    /**
     * @var Account
     */
    protected $defaultAccount;

    /**
     * @var JobService
     */
    protected $jobService;

    /**
     * @var ValidationHelper
     */
    protected $validator;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

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
            $uri = $this->getConstantsClass()::PAPER_API_ENDPOINT;
        } else {
            $uri = $this->getConstantsClass()::API_ENDPOINT;
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
     * @return AccountInfoInterface|null
     */
    public function getAccountInfo(Account $account): ?AccountInfoInterface
    {
        // TODO: Implement getAccountInfo() method.
    }

    /**
     * @param Account $account
     *
     * @return array|null
     */
    public function getPositions(Account $account): ?array
    {
        // TODO: Implement getPositions() method.
    }

    /**
     * @param OrderInfoInterface $orderInfo
     * @param Job                $job
     *
     * @return Order|null
     */
    public function createOrderFromOrderInfo(OrderInfoInterface $orderInfo, Job $job): ?Order
    {
        // TODO: Implement createOrderFromOrderInfo() method.
    }

    /**
     * @param array $message
     *
     * @return OrderInfoInterface|null
     */
    public function createOrderInfoFromMessage(array $message): ?OrderInfoInterface
    {
        // TODO: Implement createOrderInfoFromMessage() method.
    }

    /**
     * @param SyncOrdersRequest   $request
     * @param MessageBusInterface $messageBus
     * @param Job                 $job
     *
     * @return Job|null
     */
    public function fetchOrderHistory(SyncOrdersRequest $request, MessageBusInterface $messageBus, Job $job): ?Job
    {
        // TODO: Implement fetchOrderHistory() method.
    }

    /**
     * @param SyncTickersRequest  $request
     * @param MessageBusInterface $messageBus
     * @param Job                 $job
     *
     * @return Job|null
     */
    public function fetchTickers(SyncTickersRequest $request, MessageBusInterface $messageBus, Job $job): ?Job
    {
        // TODO: Implement fetchTickers() method.
    }

    /**
     * @param $account
     *
     * @return array
     */
    public function fetchPositions($account): array
    {
        // TODO: Implement fetchPositions() method.
    }

    /**
     * @param array $message
     *
     * @return TickerInterface|null
     */
    public function createTickerInfoFromMessage(array $message): ?TickerInterface
    {
        // TODO: Implement createTickerInfoFromMessage() method.
    }

    /**
     * @param TickerInterface $tickerInfo
     * @param Job             $job
     *
     * @return Ticker|null
     */
    public function createTickerFromTickerInfo(TickerInterface $tickerInfo, Job $job): ?Ticker
    {
        // TODO: Implement createTickerFromTickerInfo() method.
    }
}
