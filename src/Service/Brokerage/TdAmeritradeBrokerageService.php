<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Client\BrokerageClient;
use App\Client\BrokerageClientInterface;
use App\Constants\Brokerage\TdAmeritradeConstants;
use App\Entity\Account;
use App\Entity\Brokerage;
use App\Helper\SerializerHelper;
use App\Helper\ValidationHelper;
use App\Service\JobService;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;

/**
 * Class TdAmeritradeBrokerageService.
 */
class TdAmeritradeBrokerageService extends AbstractBrokerageService
{
    protected const BROKERAGE_CONSTANTS = TdAmeritradeConstants::class;

    /**
     * @var BrokerageClientInterface
     */
    private BrokerageClientInterface $brokerageClient;

    /**
     * TdAmeritradeBrokerageService constructor.
     *
     * @param Client                 $cache
     * @param BrokerageClient        $brokerageClient
     * @param EntityManagerInterface $entityManager
     * @param JobService             $jobService
     * @param LoggerInterface        $logger
     * @param ValidationHelper       $validator
     */
    public function __construct(
        Client $cache,
        BrokerageClient $brokerageClient,
        EntityManagerInterface $entityManager,
        JobService $jobService,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        parent::__construct($cache, $entityManager, $jobService, $logger, $validator);
        $this->brokerageClient = $brokerageClient;
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
     * @param Account $account
     *
     *@throws ClientExceptionInterface
     *
     *@return \App\DTO\Brokerage\AccountInterface|null
     */
    public function getAccountSummary(Account $account): ?\App\DTO\Brokerage\AccountInterface
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
     * @param Account $account
     *
     * @return array
     */
    public function getRequestHeaders(Account $account): array
    {
        return [];
    }
}
