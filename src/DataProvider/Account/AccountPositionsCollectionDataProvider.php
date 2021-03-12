<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataProvider\Account;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\Exception\ItemNotFoundException;
use App\Constants\Brokerage\AlpacaConstants;
use App\Entity\Account;
use App\Entity\Manager\AccountEntityManager;
use App\Helper\SerializerHelper;
use App\Service\Brokerage\BrokerageServiceProvider;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;

class AccountPositionsCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    const RESOURCE_CLASS = Account::class;
    const OPERATION_NAME = 'get_account_positions';

    /**
     * @var AccountEntityManager
     */
    private $accountManager;

    /**
     * @var BrokerageServiceProvider
     */
    private $brokerageService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AccountPositionsCollectionDataProvider constructor.
     *
     * @param AccountEntityManager     $accountManager
     * @param BrokerageServiceProvider $brokerageService
     * @param LoggerInterface          $logger
     */
    public function __construct(
        AccountEntityManager $accountManager,
        BrokerageServiceProvider $brokerageService,
        LoggerInterface $logger
    ) {
        $this->accountManager = $accountManager;
        $this->brokerageService = $brokerageService;
        $this->logger = $logger;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return self::RESOURCE_CLASS === $resourceClass && self::OPERATION_NAME === $operationName &&
            isset($context['subresource_identifiers']['id']);
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $account = $this->accountManager->findOneBy(['guid' => $context['subresource_identifiers']['id']]);
        $classMetaDataFactory = new ClassMetadataFactory(
            new YamlFileLoader(AlpacaConstants::ORDER_POSITION_INFO_SERIALIZATION_CONFIG));
        $serializer = SerializerHelper::CamelCaseToSnakeCaseNormalizer($classMetaDataFactory);

        if (!$account instanceof Account) {
            throw new ItemNotFoundException('AlpacaAccount not found');
        }

        $brokerageService = $this->brokerageService->getBrokerageService($account->getBrokerage());
        $positions = $brokerageService->getPositions($account);

        foreach ($positions as &$position) {
            $position = $serializer->denormalize(
                $position,
                AlpacaConstants::POSITION_INFO_ENTITY_CLASS,
                AlpacaConstants::REQUEST_RETURN_DATA_TYPE
            );
        }

        return $positions;
    }
}
