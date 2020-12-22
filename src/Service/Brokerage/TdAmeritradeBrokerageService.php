<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Client\BrokerageClient;
use App\Client\BrokerageClientInterface;
use App\Constants\Brokerage\TdAmeritradeConstants;
use App\DTO\Brokerage\Interfaces\AccountInfoInterface;
use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Order;
use App\Helper\SerializerHelper;
use App\Helper\ValidationHelper;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;
use Symfony\Component\Serializer\Serializer;

class TdAmeritradeBrokerageService extends AbstractBrokerageService
{
    const BROKERAGE_CONSTANTS = TdAmeritradeConstants::class;

    /**
     * @var BrokerageClientInterface
     */
    private $brokerageClient;

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(
        BrokerageClient $brokerageClient,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        $classMetaDataFactory = new ClassMetadataFactory(new YamlFileLoader(TdAmeritradeConstants::SERIALIZATION_CONFIG));
        $this->brokerageClient = $brokerageClient;
        $this->serializer = SerializerHelper::CamelCaseToSnakeCaseNormalizer($classMetaDataFactory);
        parent::__construct($validator, $logger);
    }

    /**
     * @param Brokerage $brokerage
     *
     * @return bool
     */
    public function supports(Brokerage $brokerage): bool
    {
        return $brokerage instanceof Brokerage && TdAmeritradeConstants::BROKERAGE_NAME === $brokerage->getName();
    }

    /**
     * @param OrderInfoInterface $orderInfo
     *
     * @return Order
     */
    public function createOrderFromOrderInfo(OrderInfoInterface $orderInfo): Order
    {
        // TODO: Implement createOrderFromOrderInfo() method.
    }

    /**
     * @param array $orderInfoMessage
     *
     * @return OrderInfoInterface|null
     */
    public function createOrderInfoFromMessage(array $orderInfoMessage): ?OrderInfoInterface
    {
        // TODO: Implement createOrderInfoFromMessage() method.
    }

    /**
     * @param Account $account
     *
     * @throws ClientExceptionInterface
     *
     * @return AccountInfoInterface
     */
    public function getAccountInfo(Account $account): ?AccountInfoInterface
    {
        $request = $this->brokerageClient->createRequest(
            $this->getUri(TdAmeritradeConstants::ACCOUNT_ENDPOINT, $account),
            'GET',
            $this->getRequestHeaders($account)
        );

        $response = $this->brokerageClient->sendRequest($request);

        return $this->serializer->deserialize(
            (string) $response->getBody(),
            TdAmeritradeConstants::ACCOUNT_INFO_ENTITY_CLASS,
            TdAmeritradeConstants::REQUEST_RETURN_DATA_TYPE
        );
    }

    /**
     * @param array $orderInfoArray
     *
     * @return OrderInfoInterface|null
     */
    public function getOrderInfoFromArray(array $orderInfoArray): ?OrderInfoInterface
    {
        return null;
    }

    /**
     * @param Account $account
     *
     * @return array
     */
    public function getRequestHeaders(Account $account): array
    {
        // TODO: Implement getRequestHeaders

        return [];
    }

    /**
     * @return string
     */
    public function getConstantsClass(): string
    {
        return self::BROKERAGE_CONSTANTS;
    }
}
