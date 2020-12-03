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
use App\Helper\SerializerHelper;
use App\Helper\ValidationHelper;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;
use Symfony\Component\Serializer\Serializer;

class AlpacaBrokerageService extends AbstractBrokerageService
{
    protected const BROKERAGE_CONSTANTS = AlpacaConstants::class;

    /**
     * @var BrokerageClient
     */
    private $brokerageClient;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * AlpacaBrokerageService constructor.
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
        $classMetaDataFactory = new ClassMetadataFactory(new YamlFileLoader(AlpacaConstants::SERIALIZATION_CONFIG));
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
        return $brokerage instanceof Brokerage && AlpacaConstants::BROKERAGE_NAME === $brokerage->getName();
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
            $this->getUri($account, AlpacaConstants::ACCOUNT_ENDPOINT),
            'GET',
            $this->getRequestHeaders($account)
        );

        $response = $this->brokerageClient->sendRequest($request);

        return $this->serializer->deserialize(
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
        $uri = $this->getUri($account, AlpacaConstants::ORDERS_ENDPOINT);
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
     * @param array $orderInfoArray
     *
     * @return OrderInfoInterface|null
     */
    public function getOrderInfoFromArray(array $orderInfoArray): ?OrderInfoInterface
    {
        return $this->serializer->deserialize(
            (string) json_encode($orderInfoArray),
            AlpacaConstants::ORDER_INFO_ENTITY_CLASS,
            AlpacaConstants::REQUEST_RETURN_DATA_TYPE
        );
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
