<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Client\BrokerageClient;
use App\Constants\Brokerage\AlpacaConstants;
use App\DTO\Brokerage\AccountInfoInterface;
use App\DTO\Brokerage\OrderInfoInterface;
use App\DTO\Brokerage\TickerInterface;
use App\DTO\SyncOrdersRequest;
use App\DTO\SyncTickersRequest;
use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Factory\OrderFactory;
use App\Entity\Factory\PositionFactory;
use App\Entity\Job;
use App\Entity\Order;
use App\Entity\OrderStatusType;
use App\Entity\OrderType;
use App\Entity\Position;
use App\Entity\Ticker;
use App\Helper\SerializerHelper;
use App\Helper\ValidationHelper;
use App\Message\Factory\SyncOrderHistoryMessageFactory;
use App\Message\Job\Stamp\JobItemStamp;
use App\Service\DefaultTypeService;
use App\Service\JobService;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;

/**
 * Class AlpacaBrokerageService.
 */
class AlpacaBrokerageService extends AbstractBrokerageService
{
    protected const BROKERAGE_CONSTANTS = AlpacaConstants::class;

    /**
     * @var BrokerageClient
     */
    private $brokerageClient;

    /**
     * @var DefaultTypeService
     */
    private $defaultTypeService;

    /**
     * @var PolygonBrokerageService
     */
    private $polygonService;

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
    ) {
        parent::__construct($cache, $entityManager, $jobService, $logger, $validator);
        $this->brokerageClient = $brokerageClient;
        $this->defaultTypeService = $defaultTypeService;
        $this->polygonService = $polygonService;
    }

    /**
     * @param Brokerage $brokerage
     *
     * @return bool
     */
    public function supports(Brokerage $brokerage): bool
    {
        return $brokerage instanceof Brokerage && AlpacaConstants::BROKERAGE_NAME === $brokerage->getName();
    }

    /**
     * @param OrderInfoInterface $orderInfo
     * @param Job                $job
     *
     * @return Order
     */
    public function createOrderFromOrderInfo(OrderInfoInterface $orderInfo, Job $job): Order
    {
        /** @var OrderStatusType $orderStatusType */
        $orderStatusType = $this->entityManager
            ->getRepository(OrderStatusType::class)
            ->findOneBy(['name' => $orderInfo->getStatus()]);

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
            ->setOrderStatusType($orderStatusType)
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
     * @return OrderInfoInterface|null
     */
    public function createOrderInfoFromMessage(array $message): ?OrderInfoInterface
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
     * @param OrderInfoInterface $orderInfo
     * @param Account            $account
     *
     * @throws ClientExceptionInterface
     *
     * @return Position|null
     */
    public function createPositionFromPositionHistory(OrderInfoInterface $orderInfo, Account $account): ?Position
    {
        $request = $this->brokerageClient->createRequest(
            $this->getUri('positions/'.$orderInfo->getSymbol()),
            'GET',
            $this->getRequestHeaders($account)
        );

        $response = $this->brokerageClient->sendRequest($request);

        $classMetaDataFactory = new ClassMetadataFactory(
            new YamlFileLoader(AlpacaConstants::ORDER_POSITION_INFO_SERIALIZATION_CONFIG));
        $serializer = SerializerHelper::CamelCaseToSnakeCaseNormalizer($classMetaDataFactory);

        $position = $serializer->deserialize(
            (string) json_encode($response->GetBody()), AlpacaConstants::POSITION_INFO_ENTITY_CLASS,
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
     * @param TickerInterface $tickerInfo
     * @param Job             $job
     *
     * @return Ticker
     */
    public function createTickerFromTickerInfo(TickerInterface $tickerInfo, Job $job): Ticker
    {
        return $this->polygonService->createTickerFromTickerInfo($tickerInfo, $job);
    }

    /**
     * @param array $message
     *
     * @return TickerInterface|null
     */
    public function createTickerInfoFromMessage(array $message): ?TickerInterface
    {
        return $this->polygonService->createTickerInfoFromMessage($message);
    }

    /**
     * @param string $symbol
     *
     * @return TickerInterface|null
     *
     * @deprecated
     */
    public function fetchTicker(string $symbol): ?TickerInterface
    {
        return $this->polygonService->fetchTicker($symbol);
    }

    public function getPositions(Account $account): ?array
    {
        $positions = [];
        $request = $this->brokerageClient->createRequest(
            $this->getUri(AlpacaConstants::POSITIONS_ENDPOINT, $account),
            'GET',
            $this->getRequestHeaders($account)
        );

        $response = $this->brokerageClient->sendRequest($request);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * @param string  $symbol
     * @param Account $account
     *
     * @return Position|object|null
     */
    public function getPosition(string $symbol, Account $account)
    {
        return $this->entityManager
            ->getRepository(Position::class)
            ->findOneBy([
                'symbol' => $symbol,
                'account' => $account,
            ]);
    }

    /**
     * @param string  $symbol
     * @param Account $account
     *
     * @return Ticker
     *
     * @deprecated
     */
    public function getTicker(string $symbol, Account $account)
    {
        $ticker = $this->polygonService->getTicker($symbol, $account);

        return $ticker;
    }

    /**
     * @param Account $account
     *
     * @throws ClientExceptionInterface
     *
     * @return AccountInfoInterface|null
     */
    public function getAccountInfo(Account $account): ?AccountInfoInterface
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
            (string) $response->getBody(),
            AlpacaConstants::ACCOUNT_INFO_ENTITY_CLASS,
            AlpacaConstants::REQUEST_RETURN_DATA_TYPE
        );
    }

    public function getAccountConfiguration(Account $account)
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
            (string) $response->getBody(),
            AlpacaConstants::ACCOUNT_CONFIGURATION_ENTITY_CLASS,
            AlpacaConstants::REQUEST_RETURN_DATA_TYPE
        );
    }

    public function getAccountHistory()
    {
    }

    /**
     * @param SyncOrdersRequest   $request
     * @param MessageBusInterface $messageBus
     * @param Job                 $job
     *
     * @throws ClientExceptionInterface
     *
     * @return Job|null
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
                $accountInfo = $this->getAccountInfo($account);
                $after = $accountInfo->getCreatedAt();
            }

            $params['after'] = date_format($after, \DATE_ATOM);
        }

        do {
            $uri = $this->getUri(AlpacaConstants::ORDERS_ENDPOINT, $account);
            $uri .= empty($params) ? '' : '?'.http_build_query($params);

            $request = $this->brokerageClient->createRequest(
                $uri,
                'GET',
                $this->getRequestHeaders($account)
            );

            $response = $this->brokerageClient->sendRequest($request);
            $orderHistory = json_decode((string) $response->getBody(), true);

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
     * @param SyncTickersRequest  $request
     * @param MessageBusInterface $messageBus
     * @param Job                 $job
     *
     * @throws ClientExceptionInterface
     *
     * @return Job|null
     */
    public function fetchTickers(SyncTickersRequest $request, MessageBusInterface $messageBus, Job $job): ?Job
    {
        $this->polygonService->fetchTickers($request, $messageBus, $job);
    }

    /**
     * @param Account $account
     *
     * @return array
     */
    public function getRequestHeaders(Account $account): array
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
     * @param Account|null $account |null
     */
    public function syncTickerTypes()
    {
        $this->polygonService->syncTickerTypes();
    }
}
