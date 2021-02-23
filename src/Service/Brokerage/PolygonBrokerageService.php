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
use App\DTO\Brokerage\BrokerageTickerInterface;
use App\DTO\SyncTickersRequest;
use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Factory\TickerFactory;
use App\Entity\Factory\TickerTypeFactory;
use App\Entity\Job;
use App\Entity\Ticker;
use App\Entity\TickerType;
use App\Exception\HttpMessageException;
use App\Helper\SerializerHelper;
use App\Helper\ValidationHelper;
use App\Message\Factory\SyncTickerMessageFactory;
use App\Message\Job\Stamp\JobItemStamp;
use App\Service\Entity\TickerEntityService;
use App\Service\JobService;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
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
     * @var TickerEntityService
     */
    private $tickerService;

    /**
     * PolygonBrokerageService constructor.
     *
     * @param BrokerageClient        $brokerageClient
     * @param Client                 $cache
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface        $logger
     * @param JobService             $jobService
     * @param TickerEntityService    $tickerService
     * @param ValidationHelper       $validator
     */
    public function __construct(
        BrokerageClient $brokerageClient,
        Client $cache,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        JobService $jobService,
        TickerEntityService $tickerService,
        ValidationHelper $validator
    ) {
        parent::__construct($cache, $entityManager, $jobService, $logger, $validator);
        $this->brokerageClient = $brokerageClient;
        $this->tickerService = $tickerService;
    }

    /**
     * @param Brokerage $brokerage
     *
     * @return bool
     */
    public function supports(Brokerage $brokerage): bool
    {
        return $brokerage instanceof Brokerage && PolygonContstants::BROKERAGE_NAME === $brokerage->getName();
    }

    /**
     * @param BrokerageTickerInterface $tickerInfo
     * @param Job                      $job
     *
     * @return Ticker|null
     */
    public function createTickerFromTickerInfo(BrokerageTickerInterface $tickerInfo, Job $job): ?Ticker
    {
        $account = $job->getAccount();

        /** @var TickerType $tickerType */
        $tickerType = $this->entityManager
            ->getRepository(TickerType::class)
            ->findOneBy(['code' => $tickerInfo->getType()]);

        $ticker = TickerFactory::create()
            ->setActive($tickerInfo->isActive())
            ->setExchange($tickerInfo->getExchange())
            ->setCurrency($tickerInfo->getCurrency())
            ->setMarket($tickerInfo->getMarket())
            ->setName($tickerInfo->getName())
            ->setTicker($tickerInfo->getTicker())
            ->setTickerType($tickerType)
            ->addBrokerage($account->getBrokerage());

        try {
            $uri = $this->getUri(sprintf(PolygonContstants::TICKER_DETAIL_ENDPOINT, $tickerInfo->getTicker()));

            $request = $this->brokerageClient->createRequest(
                $uri,
                'GET',
                PolygonContstants::REQUEST_HEADERS
            );

            $response = $this->brokerageClient->sendRequest($request);
            $response = json_decode((string) $response->getBody(), true);

            if (null !== $response && !empty($response)) {
                $classMetaDataFactory = new ClassMetadataFactory(
                    new YamlFileLoader(PolygonContstants::TICKER_INFO_SERIALIZATION_CONFIG));
                $serializer = SerializerHelper::JsonEncoder($classMetaDataFactory);
                $tickerDetail = $serializer->deserialize(
                    json_encode($response),
                    PolygonContstants::TICKER_DETAIL_ENTITY_CLASS,
                    AlpacaConstants::REQUEST_RETURN_DATA_TYPE
                );

                $ticker->setSector($tickerDetail->getSector());
            }
        } catch (HttpMessageException $e) {
            $this->logger->info($e);
        }

        $this->validator->validate($ticker);

        return $ticker;
    }

    /**
     * @param array $message
     *
     * @return BrokerageTickerInterface|null
     */
    public function createTickerInfoFromMessage(array $message): ?BrokerageTickerInterface
    {
        $classMetaDataFactory = new ClassMetadataFactory(
            new YamlFileLoader(PolygonContstants::TICKER_INFO_SERIALIZATION_CONFIG));
        $serializer = SerializerHelper::JsonEncoder($classMetaDataFactory);
        $tickerInfo = $serializer->deserialize(
            json_encode($message),
            PolygonContstants::TICKER_INFO_ENTITY_CLASS,
            AlpacaConstants::REQUEST_RETURN_DATA_TYPE
        );

        $this->validator->validate($tickerInfo);

        return $tickerInfo;
    }

    /**
     * @param string $symbol
     *
     *@throws ClientExceptionInterface
     *
     * @return BrokerageTickerInterface
     */
    public function fetchTicker(string $symbol): BrokerageTickerInterface
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

    /**
     * @param SyncTickersRequest  $request
     * @param MessageBusInterface $messageBus
     * @param Job                 $job
     *
     * @throws ClientExceptionInterface
     *
     * @return Job
     */
    public function fetchTickers(SyncTickersRequest $request, MessageBusInterface $messageBus, Job $job): Job
    {
        $params = $request->getParameters();
        $limit = $request->getLimit();

        do {
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

            if ($this->jobService->getJobItemCount($job) === (int) $response['count']) {
                return $job;
            }

            $pages = (int) ceil((int) $response['count'] / (int) $response['perPage']) + 1;
            $tickers = $response['tickers'];

            foreach ($tickers as $key => $tickerData) {
                $exists = $this->jobService->jobItemExists($job, $tickerData[PolygonContstants::TICKER_INFO_UNIQUE_KEY]);

                if (!$exists) {
                    $jobItem = $this->jobService->createJobItem($tickerData, $job, $tickerData[PolygonContstants::TICKER_INFO_UNIQUE_KEY]);

                    $envelope = (new Envelope(SyncTickerMessageFactory::create(
                        $job->getGuid()->toString(),
                        $jobItem->getGuid()->toString(),
                        $jobItem->getData()
                    )))->with(new JobItemStamp(
                            $job->getGuid()->toString(),
                            $jobItem->getGuid()->toString())
                    );

                    $messageBus->dispatch($envelope);
                }

                if (null !== $limit && $job->getJobItems()->count() >= $limit) {
                    $this->jobService->save($job);

                    return $job;
                }
            }

            $this->jobService->save($job);

            $params['page'] = $response['page'] + 1;
        } while ((int) $response['page'] < $pages + 1);

        return $job;
    }

    public function fetchTickerTypes()
    {
        $uri = $this->getUri(PolygonContstants::TICKER_TYPE_ENDPOINT);

        $request = $this->brokerageClient->createRequest(
            $uri,
            'GET',
            PolygonContstants::REQUEST_HEADERS
        );

        $response = $this->brokerageClient->sendRequest($request);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * @param false $returnArray
     *
     * @throws ClientExceptionInterface
     *
     * @return mixed
     */
    public function getAllTickers($returnArray = false)
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
     * @return string
     */
    public function getConstantsClass(): string
    {
        return PolygonContstants::class;
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
     * @throws ClientExceptionInterface
     */
    public function syncTickerTypes(): void
    {
        $tickerTypes = $this->fetchTickerTypes();

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
                $this->tickerService->getTickerTypeService()->save($entity);
            }
        }
        $this->logger->info('Finished fetching ticker Types');
    }
}
