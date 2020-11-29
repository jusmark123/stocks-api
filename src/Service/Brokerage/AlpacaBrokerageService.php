<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Client\BrokerageClient;
use App\Constants\Brokerage\AlpacaConstants;
use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Factory\AlpacaAccountInfoFactory;
use App\Entity\Interfaces\AccountInfoInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AlpacaBrokerageService extends AbstractBrokerageService
{
    /**
     * @var AlpacaAccountInfoFactory
     */
    private $alpacaAccountInfoFactory;

    /**
     * @var BrokerageClient
     */
    private $brokerageClient;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var [type]
     */
    private $normalizer;

    /**
     * AlpacaService Constructor.
     *
     * @param BrokerageClient $brokerageClient
     */
    public function __construct(
        BrokerageClient $brokerageClient,
                LoggerInterface $logger
    ) {
        $classMetaDataFactory = new ClassMetadataFactory(new YamlFileLoader('/opt/app-root/src/config/serialization/alpaca_account_info.yml'));
        $this->brokerageClient = $brokerageClient;
        $this->logger = $logger;
        $this->normalizer = new ObjectNormalizer($classMetaDataFactory, new CamelCaseToSnakeCaseNameConverter());
        $this->serializer = new Serializer([$this->normalizer], [new JsonEncoder()]);
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
     * @return AccountInfoInterface
     */
    public function getAccountInfo(Account $account): ?AccountInfoInterface
    {
        $request = $this->brokerageClient->createRequest(
         $this->getUri($account, AlpacaConstants::ACCOUNT_ENDPOINT),
                 'GET',
         $this->getRequestHeaders($account)
                );

        $response = $this->brokerageClient->sendRequest($request);

        return $this->serializer->deserialize((string) $response->getBody(),
                 AlpacaConstants::ACCOUNT_INFO_ENTITY_CLASS,
                 AlpacaConstants::REQUEST_RETURN_DATA_TYPE
                );

        return $accountInfo;
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
}
