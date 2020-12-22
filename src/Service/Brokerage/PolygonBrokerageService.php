<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use ApiPlatform\Core\Exception\ItemNotFoundException;
use App\Client\BrokerageClient;
use App\Constants\Brokerage\AlpacaConstants;
use App\Constants\Brokerage\PolygonContstants;
use App\DTO\Brokerage\AccountInfoInterface;
use App\DTO\Brokerage\OrderInfoInterface;
use App\DTO\Brokerage\TickerInterface;
use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Factory\TickerFactory;
use App\Entity\Factory\TickerTypeFactory;
use App\Entity\Job;
use App\Entity\Order;
use App\Entity\Ticker;
use App\Entity\TickerType;
use App\Helper\SerializerHelper;
use App\Helper\ValidationHelper;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Protocol\MessageFactory;
use App\Service\Entity\TickerEntityService;
use App\Service\JobService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;

/**
 * Class PolygonBrokerageService.
 */
class PolygonBrokerageService extends AbstractBrokerageService
{
    /**
     * @var BrokerageClient
     */
    private $brokerageClient;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TickerEntityService
     */
    private $tickerService;

    /**
     * PolygonBrokerageService constructor.
     *
     * @param BrokerageClient        $brokerageClient
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface        $logger
     * @param JobService             $jobService
     * @param MessageFactory         $messageFactory
     * @param ClientPublisher        $publisher
     * @param TickerEntityService    $tickerService
     * @param ValidationHelper       $validator
     */
    public function __construct(
        BrokerageClient $brokerageClient,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        JobService $jobService,
        TickerEntityService $tickerService,
        ValidationHelper $validator
    ) {
        $this->brokerageClient = $brokerageClient;
        $this->entityManager = $entityManager;
        $this->tickerService = $tickerService;
        parent::__construct($jobService, $logger, $validator);
    }

    public function supports(Brokerage $brokerage): bool
    {
        return false;
    }

    /**
     * @param Account $account
     *
     * @return AccountInfoInterface|null
     */
    public function getAccountInfo(Account $account): ?AccountInfoInterface
    {
        // TODO: Implement getAccountInfo() method.
    }

    /**
     * @param Account  $account
     * @param array    $filters
     * @param Job|null $job
     *
     * @return Job|null
     */
    public function getOrderHistory(Account $account, array $filters = [], Job $job = null): ?Job
    {
        // TODO: Implement getOrderHistory() method.
        return $job;
    }

    public function createOrderFromOrderInfo(OrderInfoInterface $orderInfo): ?Order
    {
        // TODO: Implement createOrderFromOrderInfo() method.
    }

    /**
     * @param array $orderInfoOrderInfoMessage
     *
     * @return OrderInfoInterface|null
     */
    public function createOrderInfoFromMessage(array $orderInfoOrderInfoMessage): ?OrderInfoInterface
    {
        // TODO: Implement createOrderInfoFromMessage() method.
    }

    /**
     * @param array $tickerMessage
     *
     * @return TickerInterface|null
     */
    public function createTickerInfoFromMessage(array $tickerMessage): ?TickerInterface
    {
        $classMetaDataFactory = new ClassMetadataFactory(
            new YamlFileLoader(PolygonContstants::TICKER_INFO_SERIALIZATION_CONFIG));
        $serializer = SerializerHelper::JsonEncoder($classMetaDataFactory);
        $tickerInfo = $serializer->deserialize(
            json_encode($tickerMessage), PolygonContstants::TICKER_INFO_ENTITY_CLASS,
            AlpacaConstants::REQUEST_RETURN_DATA_TYPE
        );

        $this->validator->validate($tickerInfo);

        return $tickerInfo;
    }

    /**
     * @param TickerInterface $tickerInfo
     * @param Account         $account
     *
     * @return Ticker|null
     */
    public function createTickerFromTickerInfo(TickerInterface $tickerInfo, Account $account): ?Ticker
    {
        $tickerType = $this->entityManager
            ->getRepository(TickerType::class)
            ->findOneBy(['name' => $tickerInfo->getType()]);

        $ticker = TickerFactory::create()
            ->setActive($tickerInfo->isActive())
            ->setCountry($tickerInfo->getCountry())
            ->setCurrency($tickerInfo->getCurrency())
            ->setDescription($tickerInfo->getDescription())
            ->setExchange($tickerInfo->getExchange())
            ->setExchangeSymbol($tickerInfo->getExchangeSymbol())
            ->setMarket($tickerInfo->getMarket())
            ->setName($tickerInfo->getName())
            ->setSector($tickerInfo->getSector())
            ->setSymbol($tickerInfo->getSymbol())
            ->setTickerType($tickerType)
            ->setUpdatedAt($tickerInfo->getUpdated())
            ->setUrl($tickerInfo->getUrl())
            ->addBrokerage($account->getBrokerage());

        $this->validator->validate($ticker);

        return $ticker;
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function syncTickerTypes(): void
    {
        $tickerTypeService = $this->tickerService->getTickerTypeService();
        $this->logger->info('Fetching ticker types...');
        $request = $this->brokerageClient->createRequest(
            $this->getUri(PolygonContstants::TICKER_TYPE_ENDPOINT),
            'GET',
            PolygonContstants::REQUEST_HEADERS
        );

        $response = $this->brokerageClient->sendRequest($request);
        $tickerTypes = json_decode((string) $response->getBody(), true);

        foreach ($tickerTypes['results'] as $types) {
            foreach ($types as $code => $type) {
                $entity = $this->entityManager
                    ->getRepository(TickerType::class)
                    ->findOneBy(['code' => $code]);

                if ($entity instanceof TickerType) {
                    continue;
                }
                $entity = TickerTypeFactory::create()
                    ->setName($type)
                    ->setCode($code);
                $tickerTypeService->save($entity);
            }
        }
        $this->logger->info('Finished fetching ticker types');
    }

    /**
     * @param Account $account
     *
     * @throws ClientExceptionInterface
     */
    public function syncTickers(Account $account): void
    {
        $tickerTypes = $this->tickerService->getTickerTypes();

        if (empty($tickerTypes)) {
            $this->syncTickerTypes();
        }

        $this->logger->info('Fetching tickers...');

        foreach ($tickerTypes as $tickerType) {
            $params = [
                'sort' => 'ticker',
                'market' => 'stocks',
            ];

            if (null !== $tickerType) {
                $params['type'] = strtolower($tickerType->getCode());
            }

            $uri = $this->getUri(PolygonContstants::TICKER_ENDPOINT);
            $uri .= '&'.http_build_query($params);

            $request = $this->brokerageClient->createRequest(
                $uri,
                'GET',
                PolygonContstants::REQUEST_HEADERS
            );

            $response = $this->brokerageClient->sendRequest($request);
            $response = json_decode((string) $response->getBody(), true);

            if (json_last_error()) {
                throw new \Exception(json_last_error_msg());
            }

            $pages = (int) ceil((int) $response['count'] / (int) $response['perPage']) + 1;

            while ((int) $response['page'] < $pages) {
                $tickers = $response['tickers'];
                foreach ($tickers as $ticker) {
                    $ticker['type'] = null === $tickerType ? $tickerType : $tickerType->getId();
                }
                $params['page'] = $response['page'] + 1;
                $uri = $this->getUri(PolygonContstants::TICKER_ENDPOINT);
                $uri .= '&'.http_build_query($params);
                $request = $this->brokerageClient->createRequest(
                    $uri,
                    'GET',
                    PolygonContstants::REQUEST_HEADERS
                );
                $response = $this->brokerageClient->sendRequest($request);
                $response = json_decode((string) $response->getBody(), true);
            }
        }
        $this->logger->info('Finished fetching tickers');
    }

    /**
     * @param string  $symbol
     * @param Account $account
     *
     * @return Ticker
     */
    public function getTicker(string $symbol, Account $account): Ticker
    {
        /** @var Ticker $ticker */
        $ticker = $this->entityManager
            ->getRepository(Ticker::class)
            ->findBrokerageTicker($symbol, [$account->getBrokerage()]);

        if (!$ticker instanceof Ticker) {
            $ticker = $this->createTickerFromTickerInfo($this->fetchTicker($symbol), $account);
        }

        return $ticker;
    }

    public function fetchTicker(string $symbol): TickerInterface
    {
        foreach (PolygonContstants::TICKER_ENDPOINTS as $key => $endpoint) {
            try {
                if ('tickers' === $key) {
                    $uri = $this->getUri($endpoint);
                    $uri .= '?'.http_build_query(['search' => $symbol]);
                } else {
                    $uri = $this->getUri(sprintf(PolygonContstants::TICKER_DETAIL_ENDPOINT, $symbol));
                }
                $request = $this->brokerageClient->createRequest($uri, 'GET', PolygonContstants::REQUEST_HEADERS);
                $response = $this->brokerageClient->sendRequest($request);
            } catch (\Exception $e) {
            }
        }

        $tickerData = json_decode((string) $response->getBody(), true);

        if (0 === $tickerData['count']) {
            throw new ItemNotFoundException('Ticker not found');
        }

        $ticker = $this->createTickerInfoFromMessage($tickerData['tickers'][0]);

        return $ticker;
    }

    private function getAllTickers($returnArray = false)
    {
        $request = $this->brokerageClient->createRequest(
            $this->getUri(PolygonContstants::ALL_TICKERS_ENDPOINT),
            'GET',
            PolygonContstants::REQUEST_HEADERS
        );
        $tickers = $this->brokerageClient->sendRequest($request);

        return json_decode($tickers, $returnArray);
    }

    /**
     * @param string       $uri
     * @param Account|null $account
     * @param array|null   $params
     *
     * @return string
     */
    protected function getUri(string $uri, ?Account $account = null, ?array $params = []): string
    {
        $uri = PolygonContstants::API_URL_BASE.$uri.PolygonContstants::URL_API_KEY_SUFFIX;

        $uri .= '&'.http_build_query($params) ?? '';

        return $uri;
    }

    /**
     * @return string
     */
    public function getConstantsClass(): string
    {
        return PolygonContstants::class;
    }
}
