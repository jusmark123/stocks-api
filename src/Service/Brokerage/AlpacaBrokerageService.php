<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Client\BrokerageClient;
use App\Constants\Brokerage\AlpacaConstants;
use App\DTO\Brokerage\AccountHistory;
use App\DTO\Brokerage\AccountHistoryInterface;
use App\DTO\Brokerage\Alpaca\AlpacaAccountActivity;
use App\DTO\Brokerage\Alpaca\AlpacaAccountConfiguration;
use App\DTO\Brokerage\Alpaca\AlpacaPosition;
use App\DTO\Brokerage\AccountHistoryRequestInterface;
use App\DTO\Brokerage\AccountInterface;
use App\DTO\Brokerage\Alpaca\Factory\AlpacaPositionFactory;
use App\DTO\Brokerage\BrokerageOrderInterface;
use App\DTO\Brokerage\BrokerageTickerInterface;
use App\DTO\SyncOrdersRequest;
use App\DTO\SyncTickersRequest;
use App\Entity\Account;
use App\Entity\Factory\OrderFactory;
use App\Entity\Factory\PositionFactory;
use App\Entity\Job;
use App\Entity\Order;
use App\Entity\OrderType;
use App\Entity\Position;
use App\Entity\Ticker;
use App\Exception\InvalidAccountConfiguration;
use App\Helper\SerializerHelper;
use App\Helper\ValidationHelper;
use App\Message\Factory\SyncOrderHistoryMessageFactory;
use App\Message\Job\Stamp\JobItemStamp;
use App\Service\DefaultTypeService;
use App\Service\JobService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class AlpacaBrokerageService.
 */
class AlpacaBrokerageService extends AbstractBrokerageService
{
    protected const BROKERAGE_CONSTANTS = AlpacaConstants::class;

    /**
     * @var BrokerageClient
     */
    private BrokerageClient $brokerageClient;

    /**
     * @var DefaultTypeService
     */
    private DefaultTypeService $defaultTypeService;

    /**
     * @var PolygonBrokerageService
     */
    private PolygonBrokerageService $polygonService;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * AlpacaBrokerageService constructor.
     *
     * @param Client                  $cache
     * @param DefaultTypeService      $defaultTypeService
     * @param EntityManagerInterface  $entityManager
     * @param BrokerageClient         $brokerageClient
     * @param JobService              $jobService
     * @param LoggerInterface         $logger
     * @param PolygonBrokerageService $polygonService
     * @param ValidationHelper        $validator
     */
    public function __construct(
        Client $cache,
        DefaultTypeService $defaultTypeService,
        EntityManagerInterface $entityManager,
        BrokerageClient $brokerageClient,
        JobService $jobService,
        LoggerInterface $logger,
        PolygonBrokerageService $polygonService,
        ValidationHelper $validator
    )
    {
        parent::__construct($cache, $entityManager, $jobService, $logger, $validator);
        $this->brokerageClient = $brokerageClient;
        $this->defaultTypeService = $defaultTypeService;
        $this->polygonService = $polygonService;
        $this->serializer = SerializerHelper::ObjectNormalizer();
    }

    /**
     * @param BrokerageOrderInterface $orderInfo
     * @param Job                     $job
     *
     * @return Order
     */
    public function createOrderFromOrderInfo(BrokerageOrderInterface $orderInfo, Job $job): Order
    {
        /** @var OrderType $orderType */
        $orderType = $this->entityManager
            ->getRepository(OrderType::class)
            ->findOneBy(['name' => $orderInfo->getType()]);

        $position = $this->getPosition($orderInfo->getSymbol(), $orderInfo->getAccount());

        $order = OrderFactory::create()
            ->setGuid(Uuid::fromString($orderInfo->getClientOrderId()))
            ->setAccount($orderInfo->getAccount())
            ->setAmountUsd($orderInfo->getFilledAvgPrice() * $orderInfo->getFilledQty())
            ->setBrokerOrderId($orderInfo->getId())
            ->setBrokerage($orderInfo->getAccount()->getBrokerage())
            ->setCreatedAt($orderInfo->getCreatedAt())
            ->setCreatedby($orderInfo->getUser()->getUsername())
            ->setFees(0.00)
            ->setFilledQty($orderInfo->getFilledQty())
            ->setModifiedAt($orderInfo->getUpdatedAt())
            ->setModifiedBy($orderInfo->getUser()->getUsername())
            ->setOrderType($orderType)
            ->setPosition($position)
            ->setSide($orderInfo->getSide())
            ->setSource($orderInfo->getSource())
            ->setSymbol($orderInfo->getSymbol())
            ->setUser($orderInfo->getUser());

        $this->validator->validate($order);

        return $order;
    }

    /**
     * @param array $message
     *
     * @return BrokerageOrderInterface|null
     */
    public function createOrderInfoFromMessage(array $message): ?BrokerageOrderInterface
    {
        $classMetaDataFactory = new ClassMetadataFactory(
            new YamlFileLoader(AlpacaConstants::ORDER_INFO_SERIALIZATION_CONFIG)
        );
        $serializer = SerializerHelper::CamelCaseToSnakeCaseNormalizer($classMetaDataFactory);
        $orderInfo = $serializer->deserialize(
            json_encode($message),
            AlpacaConstants::ORDER_INFO_ENTITY_CLASS,
            AlpacaConstants::REQUEST_RETURN_DATA_TYPE
        );

        $this->validator->validate($orderInfo);

        return $orderInfo;
    }

    /**
     * @param BrokerageOrderInterface $orderInfo
     * @param Account                 $account
     *
     * @return Position|null
     * @throws ClientExceptionInterface
     *
     */
    public function createPositionFromPositionHistory(BrokerageOrderInterface $orderInfo, Account $account): ?Position
    {
        $request = $this->brokerageClient->createRequest(
            $this->getUri('positions/' . $orderInfo->getSymbol()),
            'GET',
            $this->getRequestHeaders($account)
        );

        $response = $this->brokerageClient->sendRequest($request);

        $classMetaDataFactory = new ClassMetadataFactory(
            new YamlFileLoader(AlpacaConstants::ORDER_POSITION_INFO_SERIALIZATION_CONFIG));
        $serializer = SerializerHelper::CamelCaseToSnakeCaseNormalizer($classMetaDataFactory);

        $position = $serializer->deserialize(
            (string)json_encode($response->GetBody()), AlpacaConstants::POSITION_INFO_ENTITY_CLASS,
            AlpacaConstants::REQUEST_RETURN_DATA_TYPE
        );

        if (!$position instanceof Position) {
            $position = PositionFactory::create()
                ->setAccount($account)
                ->setSide($orderInfo->getSide())
                ->setQty($orderInfo->getFilledQty())
                ->setSymbol($orderInfo->getSymbol());
        }

        return $position;
    }

    /**
     * @param Account $account
     *
     * @return
     * @throws InvalidAccountConfiguration
     *
     * @throws ClientExceptionInterface
     */
    public function fetchAccountConfiguration(Account $account): ?AlpacaAccountConfiguration
    {
        $request = $this->brokerageClient->createRequest(
            $this->getUri(AlpacaConstants::ACCOUNT_CONFIG_ENDPOINT, $account),
            'GET',
            $this->getRequestHeaders($account)
        );

        $response = $this->brokerageClient->sendRequest($request);

        $classMetaDataFactory = new ClassMetadataFactory(
            new YamlFileLoader(AlpacaConstants::ACCOUNT_CONFIGURATION__SERIALIZATION_CONFIG));
        $serializer = SerializerHelper::CamelCaseToSnakeCaseNormalizer($classMetaDataFactory);

        return $serializer->deserialize(
            (string)$response->getBody(),
            AlpacaConstants::ACCOUNT_CONFIGURATION_ENTITY_CLASS,
            AlpacaConstants::REQUEST_RETURN_DATA_TYPE
        );
    }

    /**
     * @param AccountHistoryRequestInterface $request
     *
     * @return mixed
     *
     * @throws InvalidAccountConfiguration|ClientExceptionInterface
     */
    public function fetchAccountHistory(AccountHistoryRequestInterface $request): ?AccountHistoryInterface
    {
        $uri = $this->getUri(AlpacaConstants::ORDERS_ENDPOINT, $request->getAccount());
        $uri .= empty($params) ? '' : '?' . http_build_query($request->getParameters());

        $request = $this->brokerageClient->createRequest(
            'GET',
            $uri,
            $this->getRequestHeaders($request->getAccount()));
        $response = $this->brokerageClient->sendRequest($request);

        return $this->serializer->denormalize(
            (string)$response->getBody(), AlpacaAccountActivity::class, 'json');
    }

    /**
     * @param Account $account
     *
     * @return AccountInterface|null
     *
     * @throws ClientExceptionInterface|InvalidAccountConfiguration
     */
    public function fetchAccount(Account $account): ?AccountInterface
    {
        $request = $this->brokerageClient->createRequest(
            $this->getUri(AlpacaConstants::ACCOUNT_ENDPOINT, $account),
            'GET',
            $this->getRequestHeaders($account)
        );

        $response = $this->brokerageClient->sendRequest($request);

        $classMetaDataFactory = new ClassMetadataFactory(
            new YamlFileLoader(AlpacaConstants::ACCOUNT_INFO_SERIALIZATION_CONFIG));
        $serializer = SerializerHelper::CamelCaseToSnakeCaseNormalizer($classMetaDataFactory);

        return $serializer->deserialize(
            (string)$response->getBody(),
            AlpacaConstants::ACCOUNT_INFO_ENTITY_CLASS,
            AlpacaConstants::REQUEST_RETURN_DATA_TYPE
        );
    }

    /**
     * @param SyncOrdersRequest   $request
     * @param MessageBusInterface $messageBus
     * @param Job                 $job
     *
     * @return Job|null
     * @throws InvalidAccountConfiguration
     *
     * @throws ClientExceptionInterface
     */
    public function fetchOrderHistory(SyncOrdersRequest $request, MessageBusInterface $messageBus, Job $job): Job
    {
        $account = $request->getAccount();
        $params = $request->getParameters();
        $limit = $request->getLimit();

        $params['direction'] = 'ASC';

        if (!\array_key_exists('limit', $params)) {
            $params['limit'] = AlpacaConstants::ORDER_HISTORY_DEFAULT_PAGE_LIMIT;
        }

        if (!\array_key_exists('after', $params)) {
            $lastItem = $job->getJobItems()->last();

            if ($lastItem) {
                $after = new \DateTime($lastItem->getData()['created_at']);
            } else {
                $accountSummary = $this->getAccountSummary($account);
                $after = $accountSummary->getCreatedAt();
            }

            $params['after'] = date_format($after, \DATE_ATOM);
        }

        do {
            $uri = $this->getUri(AlpacaConstants::ORDERS_ENDPOINT, $account);
            $uri .= empty($params) ? '' : '?' . http_build_query($params);

            $request = $this->brokerageClient->createRequest(
                $uri,
                'GET',
                $this->getRequestHeaders($account)
            );

            $response = $this->brokerageClient->sendRequest($request);
            $orderHistory = json_decode((string)$response->getBody(), true);

            if (json_last_error()) {
                throw new \Exception(json_last_error_msg());
            }

            foreach ($orderHistory as $key => $orderData) {
                $exists = $this->jobService->jobItemExists($job, $orderData[AlpacaConstants::ORDER_INFO_UNIQUE_KEY]);

                if (!$exists) {
                    $jobItem = $this->jobService->createJobItem($orderData, $job, $orderData['client_order_id']);

                    $envelope = (new Envelope(SyncOrderHistoryMessageFactory::create(
                        $job->getGuid()->toString(),
                        $jobItem->getGuid()->toString(),
                        $jobItem->getData()
                    )))->with(new JobItemStamp(
                            $job->getGuid()->toString(),
                            $jobItem->getGuid()->toString())
                    );

                    $messageBus->dispatch($envelope);
                }

                if (null !== $limit && $job->getJobItems()->count() >= $limit) {
                    $this->jobService->save($job);

                    return $job;
                }
            }

            $this->jobService->save($job);

            $dates = array_map(function ($v) {
                return new \DateTime($v);
            }, array_column($orderHistory, 'created_at'));

            $params['after'] = date_format(max($dates), \DATE_ATOM);
        } while (\count($orderHistory) === $params['limit']);

        return $job;
    }

    /**
     * @param Account $account
     *
     * @return array|null
     *
     * @throws InvalidAccountConfiguration|ExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function fetchPositions(Account $account): ?array
    {
        $request = $this->brokerageClient->createRequest(
            $this->getUri(AlpacaConstants::POSITIONS_ENDPOINT, $account),
            'GET',
            $this->getRequestHeaders($account)
        );

        $response = $this->brokerageClient->sendRequest($request);
        $data = \json_decode((string)$response->getBody(), true);

        foreach ($data as $position) {
            /** @var Ticker $ticker */
            $ticker = $this->entityManager
                ->getRepository(Ticker::class)
                ->findOneBy(['ticker' => $position['symbol']]);

            $position = AlpacaPositionFactory::createEntity($position, $ticker, $account);
            $this->entityManager->persist($position);
            $positions[] = $position;
        }

        $this->entityManager->flush();

        return $positions;
    }

    /**
     * @param Account $account
     *
     * @return array
     */
    protected function getRequestHeaders(Account $account): array
    {
        return [
            AlpacaConstants::REQUEST_HEADER_API_KEY => $account->getApiKey(),
            AlpacaConstants::REQUEST_HEADER_API_SECRET_KEY => $account->getApiSecret(),
        ];
    }

    /**
     * @return string
     */
    public function getConstantsClass(): string
    {
        return self::BROKERAGE_CONSTANTS;
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function syncTickerTypes()
    {
        $this->polygonService->syncTickerTypes();
    }
}
