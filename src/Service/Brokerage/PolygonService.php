<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Client\BrokerageClient;
use App\Constants\Brokerage\PolygonContstants;
use App\Constants\Transport\Queue;
use App\DTO\Brokerage\Interfaces\AccountInfoInterface;
use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Factory\TickerTypeFactory;
use App\Entity\Order;
use App\Entity\TickerType;
use App\Helper\ValidationHelper;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Protocol\MessageFactory;
use App\Service\Entity\TickerTypeEntityService;
use Psr\Log\LoggerInterface;

/**
 * Class PolygonService.
 */
class PolygonService extends AbstractBrokerageService
{
    /**
     * @var AlpacaBrokerageService
     */
    private $alpacBeokerageService;

    /**
     * @var BrokerageClient
     */
    private $brokerageClient;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @var ClientPublisher
     */
    private $publisher;

    /**
     * @var TickerTypeEntityService
     */
    private $tickerTypeService;

    /**
     * PolygonService constructor.
     *
     * @param AlpacaBrokerageService  $alpacaBrokerageService
     * @param BrokerageClient         $brokerageClient
     * @param ClientPublisher         $publisher
     * @param LoggerInterface         $logger
     * @param TickerTypeEntityService $tickerTypeService
     * @param ValidationHelper        $validator
     */
    public function __construct(
        AlpacaBrokerageService $alpacaBrokerageService,
        BrokerageClient $brokerageClient,
        ClientPublisher $publisher,
        LoggerInterface $logger,
        MessageFactory $messageFactory,
        TickerTypeEntityService $tickerTypeService,
        ValidationHelper $validator
    ) {
        $this->alpacBeokerageService = $alpacaBrokerageService;
        $this->brokerageClient = $brokerageClient;
        $this->messageFactory = $messageFactory;
        $this->publisher = $publisher;
        $this->tickerTypeService = $tickerTypeService;
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
        return null;
    }

    /**
     * @param OrderInfoInterface $orderInfo
     *
     * @return Order|null
     */
    public function createOrderFromOrderInfo(OrderInfoInterface $orderInfo): ?Order
    {
        return null;
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
     * @throws \Psr\Http\Client\ClientExceptionInterface
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
            foreach($types as $code => $type) {
                $entity = $this->tickerTypeService->getEntityManager()->getEntityRepository()->findOneBy(['code' => $code]);

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
     * @throws \App\MessageClient\Exception\InvalidMessage
     * @throws \App\MessageClient\Exception\PublishException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function syncTickers(): void
    {
        $tickerTypes = $this->tickerTypeService->getEntityManager()->findAll();

        if(empty($tickerTypes)) {
            $this->syncTickerTypes();
        }

        $this->logger->info('Fetching tickers...');
        $tickerTypes[] = null;

        foreach($tickerTypes as $tickerType) {
            $params = [
                'sort' => 'ticker',
                'market' => 'stocks',
            ];

            if(null !== $tickerType) {
                $params['type'] = strtolower($tickerType->getCode());
            }

            $uri = $this->getUri(PolygonContstants::TICKER_ENDPOINT);
            $uri .= '&' . http_build_query($params);

            $request = $this->brokerageClient->createRequest(
                $uri,
                'GET',
                PolygonContstants::REQUEST_HEADERS
            );

            $response = $this->brokerageClient->sendRequest($request);
            $response = json_decode((string)$response->getBody(), true);
            $pages = (int) ceil((int)$response['count'] / (int)$response['perPage']) + 1;

            while ((int)$response['page'] < $pages) {
                $tickers = $response['tickers'];
                foreach ($tickers as $ticker) {
                    $ticker['type'] = null === $tickerType ? $tickerType : $tickerType->getId();
                    $packet = $this->messageFactory->createPacket(
                        Queue::TICKERS_PERSISTENT_ROUTING_KEY,
                        json_encode($ticker),
                        [Queue::SYSTEM_PUBLISHER_HEADER_NAME => Queue::SYSTEM_PUBLISHER_NAME]
                    );
                    $this->publisher->publish($packet);
                }
                $params['page'] = $response['page'] + 1;
                $uri = $this->getUri(PolygonContstants::TICKER_ENDPOINT);
                $uri .= '&' . http_build_query($params);
                $request = $this->brokerageClient->createRequest(
                    $uri,
                    'GET',
                    PolygonContstants::REQUEST_HEADERS
                );
                $response = $this->brokerageClient->sendRequest($request);
                $response = json_decode((string)$response->getBody(), true);
            }
        }
        $this->logger->info('Finished fetching tickers');
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
