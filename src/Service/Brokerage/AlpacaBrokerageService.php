<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Client\BrokerageClient;
use App\Constants\Brokerage\AlpacaConstants;
use App\Constants\Transport\Queue;
use App\DTO\Brokerage\AccountInfoInterface;
use App\DTO\Brokerage\OrderInfoInterface;
use App\DTO\Brokerage\TickerInterface;
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
use App\MessageClient\Exception\InvalidMessage;
use App\MessageClient\Exception\PublishException;
use App\Service\DefaultTypeService;
use App\Service\JobService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;

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
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var PolygonBrokerageService
     */
    private $polygonService;

    /**
     * AlpacaBrokerageService constructor.
     *
     * @param DefaultTypeService      $defaultTypeService
     * @param EntityManagerInterface  $entityManager
     * @param BrokerageClient         $brokerageClient
     * @param JobService              $jobService
     * @param LoggerInterface         $logger
     * @param PolygonBrokerageService $polygonService
     * @param ValidationHelper        $validator
     */
    public function __construct(
        DefaultTypeService $defaultTypeService,
        EntityManagerInterface $entityManager,
        BrokerageClient $brokerageClient,
        JobService $jobService,
        LoggerInterface $logger,
        PolygonBrokerageService $polygonService,
        ValidationHelper $validator
    ) {
        $this->brokerageClient = $brokerageClient;
        $this->defaultTypeService = $defaultTypeService;
        $this->entityManager = $entityManager;
        $this->polygonService = $polygonService;
        parent::__construct($jobService, $logger, $validator);
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
     *
     * @return Order
     */
    public function createOrderFromOrderInfo(OrderInfoInterface $orderInfo): Order
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
            ->setAccount($orderInfo->getAccount())
            ->setAmountUsd($orderInfo->getFilledAvgPrice() * $orderInfo->getFilledQty())
            ->setBrokerOrderId($orderInfo->getClientOrderId())
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
     * @param array $orderInfoMessage
     *
     * @return OrderInfoInterface|null
     */
    public function createOrderInfoFromMessage(array $orderInfoMessage): ?OrderInfoInterface
    {
        $classMetaDataFactory = new ClassMetadataFactory(new YamlFileLoader(AlpacaConstants::ORDER_INFO_SERIALIZATION_CONFIG));
        $serializer = SerializerHelper::CamelCaseToSnakeCaseNormalizer($classMetaDataFactory);
        $orderInfo = $serializer->deserialize(
            (string) json_encode($orderInfoMessage),
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
    public function createPositionFromOrderInfo(OrderInfoInterface $orderInfo, Account $account): ?Position
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
     * @param Account         $account
     *
     * @return Ticker
     */
    public function createTickerFromTickerInfo(TickerInterface $tickerInfo, Account $account): Ticker
    {
        return $this->polygonService->createTickerFromTickerInfo($tickerInfo, $account);
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

        $classMetaDataFactory = new ClassMetadataFactory(new YamlFileLoader(AlpacaConstants::ACCOUNT_INFO_SERIALIZATION_CONFIG));
        $serializer = SerializerHelper::CamelCaseToSnakeCaseNormalizer($classMetaDataFactory);

        return $serializer->deserialize(
            (string) $response->getBody(),
            AlpacaConstants::ACCOUNT_INFO_ENTITY_CLASS,
            AlpacaConstants::REQUEST_RETURN_DATA_TYPE
        );
    }

    /**
     * @param Account  $account
     * @param array    $filters
     * @param Job|null $job
     *
     * @throws PublishException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ClientExceptionInterface
     * @throws InvalidMessage
     *
     * @return Job|null
     */
    public function getOrderHistory(Account $account, array $filters = [], Job $job = null): ?Job
    {
        $headers = [Queue::SYSTEM_PUBLISHER_HEADER_NAME => Queue::SYSTEM_PUBLISHER_NAME];

        if (!$account->hasInfo()) {
            $account->setAccountInfo($this->getAccountInfo($account));
        }

        if (!\array_key_exists('limit', $filters)) {
            $filters['limit'] = AlpacaConstants::ORDER_HISTORY_DEFAULT_PAGE_LIMIT;
        }

        if (!\array_key_exists('after', $filters)) {
            $accountInfo = $this->getAccountInfo($account);
            $filters['after'] = date_format($accountInfo->getCreatedAt(), DATE_ATOM);
        }

        do {
            $uri = $this->getUri(AlpacaConstants::ORDERS_ENDPOINT, $account);
            $uri .= empty($filters) ? '' : '?'.http_build_query($filters);

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

            foreach ($orderHistory as $orderData) {
                $jobItem = $this->jobService->createJobItem($orderData, $job);
                $this->jobService->publishJobItem($jobItem, $headers, Queue::ORDER_INFO_PERSISTENT_ROUTING_KEY);
            }

            if (!empty($respnose)) {
                $lastOrder = end($orderHistory);
                $filters['after'] = $lastOrder['created_at'];
            }

            $this->jobService->save($job);
        } while (!empty($response));

        return $orderHistory;
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
}
