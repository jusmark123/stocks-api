<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Client\BrokerageClient;
use App\Constants\Brokerage\PolygonContstants;
use App\DTO\Brokerage\AccountHistoryInterface;
use App\DTO\Brokerage\AccountHistoryRequestInterface;
use App\DTO\Brokerage\AccountInterface;
use App\DTO\Brokerage\Alpaca\AlpacaAccountConfiguration;
use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Job;
use App\Exception\InvalidAccountConfiguration;
use App\Helper\SerializerHelper;
use App\Helper\ValidationHelper;
use App\Service\JobService;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class AbstractBrokerageService.
 */
abstract class AbstractBrokerageService implements BrokerageServiceInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected const BROKERAGE_CONSTANTS = '';

    /**
     * @var BrokerageClient
     */
    protected BrokerageClient $brokerageClient;

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
     * @var SerializerInterface
     */
    protected SerializerInterface $serializer;

    /**
     * AbstractBrokerageService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param JobService             $jobService
     * @param LoggerInterface        $logger
     * @param ValidationHelper       $validator
     */
    public function __construct(
        BrokerageClient $brokerageClient,
        Client $cache,
        EntityManagerInterface $entityManager,
        JobService $jobService,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        $this->brokerageClient = $brokerageClient;
        $this->cache = $cache;
        $this->entityManager = $entityManager;
        $this->jobService = $jobService;
        $this->logger = $logger;
        $this->serializer = SerializerHelper::ObjectNormalizer();
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

    /**
     * @param string  $method
     * @param string  $endpoint
     * @param Account $account
     * @param bool    $assoc
     *
     * @throws ClientExceptionInterface
     * @throws InvalidAccountConfiguration
     *
     * @return string|array
     */
    protected function sendRequest(string $method, string $endpoint, Account $account, bool $assoc = false)
    {
        $request = $this->brokerageClient->createRequest(
            $this->getUri($endpoint, $account),
            $method,
            $this->getRequestHeaders($account)
        );
        $response = $this->brokerageClient->sendRequest($request);

        if ($assoc) {
            return json_decode((string) $response->getBody(), true);
        }

        return (string) $response->getBody();
    }

    /**
     * @param string|array $data
     * @param string       $entityClass
     * @param string       $format
     *
     * @return mixed
     */
    protected function deserializeData($data, string $entityClass, string $format = 'json')
    {
        if (\is_array($data)) {
            return $this->serializer->denormalize($data, $entityClass, $format);
        }

        return $this->serializer->deserialize($data, $entityClass, $format);
    }
}
