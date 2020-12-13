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
use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Factory\OrderFactory;
use App\Entity\Factory\PositionFactory;
use App\Entity\Order;
use App\Entity\OrderStatusType;
use App\Entity\OrderType;
use App\Entity\PositionSideType;
use App\Entity\Ticker;
use App\Helper\SerializerHelper;
use App\Helper\ValidationHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
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
     * @param EntityManagerInterface  $entityManager
     * @param BrokerageClient         $brokerageClient
     * @param LoggerInterface         $logger
     * @param PolygonBrokerageService $polygonService
     * @param ValidationHelper        $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        BrokerageClient $brokerageClient,
        LoggerInterface $logger,
        PolygonBrokerageService $polygonService,
        ValidationHelper $validator
    ) {
        $this->brokerageClient = $brokerageClient;
        $this->entityManager = $entityManager;
        $this->polygonService = $polygonService;
        parent::__construct($logger, $validator);
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
        $orderStatusType = $this->entityManager
            ->getRepository(OrderStatusType::class)
            ->findOneBy(['name' => $orderInfo->getStatus()]);

        $orderType = $this->entityManager
            ->getRepository(OrderType::class)
            ->findOneBy(['name' => $orderInfo->getType()]);

//        $position = $this->createPositionFromOrderInfo();

        $order = OrderFactory::create()
            ->setGuid(Uuid::fromString($orderInfo->getClientOrderId()))
            ->setAccount($orderInfo->getAccount())
            ->setAmountUsd($orderInfo->getFilledAvgPrice() * $orderInfo->getFilledQty())
            ->setAvgCost($orderInfo->getFilledAvgPrice())
            ->setBrokerOrderId($orderInfo->getId())
            ->setBrokerage($orderInfo->getAccount()->getBrokerage())
            ->setCreatedAt($orderInfo->getCreatedAt())
            ->setCreatedby($orderInfo->getUser()->getUsername())
            ->setUser($orderInfo->getUser())
            ->setFees(0.00)
            ->setFilledQty($orderInfo->getFilledQty())
            ->setModifiedAt($orderInfo->getUpdatedAt())
            ->setModifiedBy($orderInfo->getUser()->getUsername())
            ->setOrderStatusType($orderStatusType)
            ->setOrderType($orderType)
            ->setPosition($position)
            ->setSide($orderInfo->getSide())
            ->setSource($orderInfo->getSource()
            ->setUser($orderInfo->getUser()));

        $this->validator->validate($order);

        return $order;
    }

    /**
     * @param array   $orderInfoArray
     * @param Account $account
     *
     * @return OrderInfoInterface|null
     */
    public function createOrderInfoFromMessage(array $orderInfoArray): ?OrderInfoInterface
    {
        $classMetaDataFactory = new ClassMetadataFactory(new YamlFileLoader(AlpacaConstants::ORDER_INFO_SERIALIZATION_CONFIG));
        $serializer = SerializerHelper::CamelCaseToSnakeCaseNormalizer($classMetaDataFactory);
        $orderInfo = $serializer->deserialize(
            (string) json_encode($orderInfoArray), AlpacaConstants::ORDER_INFO_ENTITY_CLASS,
            AlpacaConstants::REQUEST_RETURN_DATA_TYPE
        );

        $this->validator->validate($orderInfo);

        return $orderInfo;
    }

    public function createPositionFromOrderInfo(OrderInfoInterface $orderInfo)
    {
        $ticker = $this->getTicker($orderInfo->getSymbol());

        $positionSideType = $this->entityManager
            ->getRepository(PositionSideType::class)
            ->findOneBy(['name' => $orderInfo->getSide()]);

        return PositionFactory::create()
            ->setAccount($orderInfo->getAccount())
            ->setPositionSideType($positionSideType)
            ->setExchange($ticker->getExchange())
            ->setQty($orderInfo->getFilledQty())
            ->setTicker($ticker);
    }

    public function getTicker(string $symbol, Account $account)
    {
        /** @var Ticker $ticker */
        $ticker = $this->entityManager
            ->getRepository(Ticker::class)
            ->findOneBy(['ticker' => $symbol]);

        if (!$ticker instanceof Ticker) {
            $uri = $this->getUri(sprintf(AlpacaConstants::ASSETS_ENDPOINT.'/%s', $symbol));
            $request = $this->createRequest(
              $uri,
                'GET',
                $this->getRequestHeaders($account)
            );
            $response = $this->brokerageClient->sendRequest($request);
            $ticker = $response->getBody();
        }
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
     * @param Account $account
     * @param array   $filters
     *
     * @throws ClientExceptionInterface
     *
     * @return array
     */
    public function getOrderHistory(Account $account, array $filters = []): array
    {
        $uri = $this->getUri(AlpacaConstants::ORDERS_ENDPOINT, $account);
        $uri .= empty($filters) ? '' : '?'.http_build_query($filters);

        $request = $this->brokerageClient->createRequest(
            $uri,
            'GET',
            $this->getRequestHeaders($account)
        );

        $response = $this->brokerageClient->sendRequest($request);

        $response = json_decode((string) $response->getBody(), true);

        if (json_last_error()) {
            throw new \Exception(json_last_error_msg());
        }

        return $response;
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
