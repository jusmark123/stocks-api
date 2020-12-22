<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Client\BrokerageClient;
use App\Client\BrokerageClientInterface;
use App\Constants\Brokerage\TdAmeritradeConstants;
use App\DTO\Brokerage\OrderInfoInterface;
use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Job;
use App\Entity\Order;
use App\Helper\SerializerHelper;
use App\Helper\ValidationHelper;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;

/**
 * Class TdAmeritradeBrokerageService.
 */
class TdAmeritradeBrokerageService
{
    const BROKERAGE_CONSTANTS = TdAmeritradeConstants::class;

    /**
     * @var BrokerageClientInterface
     */
    private $brokerageClient;

    /**
     * TdAmeritradeBrokerageService constructor.
     *
     * @param BrokerageClient  $brokerageClient
     * @param LoggerInterface  $logger
     * @param ValidationHelper $validator
     */
    public function __construct(
        BrokerageClient $brokerageClient,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
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
        return $brokerage instanceof Brokerage && TdAmeritradeConstants::BROKERAGE_NAME === $brokerage->getName();
    }

    /**
     * @param \App\DTO\Brokerage\OrderInfoInterface $orderInfo
     *
     * @return Order|null
     */
    public function createOrderFromOrderInfo(OrderInfoInterface $orderInfo): ?Order
    {
        // TODO: Implement createOrderFromOrderInfo() method.
    }

    /**
     * @param array $orderInfoMessage
     *
     * @return \App\DTO\Brokerage\OrderInfoInterface|null
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
     * @return \App\DTO\Brokerage\AccountInfoInterface|null
     */
    public function getAccountInfo(Account $account): ?\App\DTO\Brokerage\AccountInfoInterface
    {
        $classMetaDataFactory = new ClassMetadataFactory(
            new YamlFileLoader(TdAmeritradeConstants::ORDER_INFO_SERIALIZATION_CONFIG));
        $serializer = SerializerHelper::CamelCaseToSnakeCaseNormalizer($classMetaDataFactory);
        $request = $this->brokerageClient->createRequest(
            $this->getUri(TdAmeritradeConstants::ACCOUNT_ENDPOINT, $account),
            'GET',
            $this->getRequestHeaders($account)
        );

        $response = $this->brokerageClient->sendRequest($request);

        return $serializer->deserialize(
            (string) $response->getBody(),
            TdAmeritradeConstants::ACCOUNT_INFO_ENTITY_CLASS,
            TdAmeritradeConstants::REQUEST_RETURN_DATA_TYPE
        );
    }

    /**
     * @param Job     $job
     * @param Account $account
     * @param array   $filters
     *
     * @return array
     */
    public function getOrderHistory(Account $account, ?array $filters = [], ?Job $job = null): array
    {
        // TODO: Implement getOrderHistory() method.
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
