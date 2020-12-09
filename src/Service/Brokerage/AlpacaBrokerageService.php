<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Client\BrokerageClient;
use App\Constants\Brokerage\AlpacaConstants;
use App\DTO\Brokerage\Interfaces\AccountInfoInterface;
use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Factory\OrderFactory;
use App\Entity\Manager\SourceEntityManager;
use App\Entity\Order;
use App\Helper\SerializerHelper;
use App\Helper\ValidationHelper;
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
     * @var SourceEntityManager
     */
    private $sourceEntityManager;

    /**
     * AlpacaBrokerageService constructor.
     *
     * @param SourceEntityManager $sourceEntityManager
     * @param BrokerageClient     $brokerageClient
     * @param LoggerInterface     $logger
     * @param ValidationHelper    $validator
     */
    public function __construct(
        SourceEntityManager $sourceEntityManager,
        BrokerageClient $brokerageClient,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        $this->sourceEntityManager = $sourceEntityManager;
        $this->brokerageClient = $brokerageClient;
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
        $order = OrderFactory::create()
            ->setBrokerOrderId($orderInfo->getId())
            ->setBrokerage($orderInfo->getAccount()->getBrokerage())
            ->setAccount($orderInfo->getAccount())
            ->setUser($orderInfo->getUser())
            ->setAmountUsd($orderInfo->getFilledAvgPrice() * $orderInfo->getFilledQty())
            ->setFilledQty($orderInfo->getFilledQty())
            ->setAvgCost($orderInfo->getFilledAvgPrice());

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
        $orderInfo->getCreatedBy('system_user')->getModifiedBy('system_user');
        $this->validator->validate($orderInfo);

        return $orderInfo;
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
     * @return array|null
     */
    public function getOrderHistory(Account $account, array $filters = []): ?array
    {
        $uri = $this->getUri(AlpacaConstants::ACCOUNT_ENDPOINT, $account);
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
