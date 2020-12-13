<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Client\BrokerageClient;
use App\Constants\Brokerage\PolygonContstants;
use App\DTO\Brokerage\AccountInfoInterface;
use App\DTO\Brokerage\OrderInfoInterface;
use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Factory\TickerTypeFactory;
use App\Entity\Job;
use App\Entity\Order;
use App\Entity\Ticker;
use App\Entity\TickerType;
use App\Helper\ValidationHelper;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Exception\InvalidMessage;
use App\MessageClient\Exception\PublishException;
use App\MessageClient\Protocol\MessageFactory;
use App\Service\Entity\TickerEntityService;
use App\Service\Entity\TickerTypeEntityService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
     * @var MessageFactory
     */
    private $messageFactory;

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
     * @param MessageFactory         $messageFactory
     * @param TickerEntityService    $tickerService
     * @param ValidationHelper       $validator
     */
    public function __construct(
        BrokerageClient $brokerageClient,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        MessageFactory $messageFactory,
        TickerEntityService $tickerService,
        ValidationHelper $validator
    ) {
        $this->brokerageClient = $brokerageClient;
        $this->entityManager = $entityManager;
        $this->messageFactory = $messageFactory;
        $this->tickerService = $tickerService;
        parent::__construct($logger, $validator);
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
     * @param Account $account
     * @param array   $filters
     *
     * @return array
     */
    public function getOrderHistory(Account $account, array $filters): array
    {
        // TODO: Implement getOrderHistory() method.
    }

    public function createOrderFromOrderInfo(OrderInfoInterface $orderInfo): ?Order
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
        return null;
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function syncTickerTypes(): void
    {
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
                $this->tickerTypeService->save($entity);
            }
        }
        $this->logger->info('Finished fetching ticker types');
    }

    /**
     * @param Job $job
     *
     * @throws InvalidMessage
     * @throws PublishException
     * @throws ClientExceptionInterface
     */
    public function syncTickers(Job $job): void
    {
        $tickerTypes = $this->tickerService->getTickerTypes();

        if (empty($tickerTypes)) {
            $this->syncTickerTypes();
        }

        $tickers = $this->tickerService->getTickers();

        $this->logger->info('Fetching tickers...');

        $tickerTypes[] = null;
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

    public function getTicker(string $symbol): Ticker
    {
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
     *
     * @return string
     */
    protected function getUri(string $uri, ?Account $account = null): string
    {
        return PolygonContstants::API_URL_BASE.$uri.PolygonContstants::URL_API_KEY_SUFFIX;
    }

    /**
     * @return string
     */
    public function getConstantsClass(): string
    {
        return PolygonContstants::class;
    }
}
