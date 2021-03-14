<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Client\BrokerageClient;
use App\Constants\Brokerage\AlpacaConstants;
use App\DTO\Brokerage\AccountHistoryInterface;
use App\DTO\Brokerage\AccountHistoryRequestInterface;
use App\DTO\Brokerage\AccountInterface;
use App\DTO\Brokerage\Alpaca\AlpacaAccount;
use App\DTO\Brokerage\Alpaca\AlpacaAccountConfiguration;
use App\DTO\Brokerage\Alpaca\AlpacaAccountNonTradeActivity;
use App\DTO\Brokerage\Alpaca\AlpacaAccountTradeActivity;
use App\DTO\Brokerage\Alpaca\AlpacaPosition;
use App\DTO\Brokerage\Alpaca\Factory\AlpacaOrderFactory;
use App\DTO\Brokerage\Alpaca\Factory\AlpacaPositionFactory;
use App\DTO\Brokerage\BrokerageOrderEventInterface;
use App\DTO\Brokerage\BrokerageOrderInterface;
use App\Entity\Account;
use App\Entity\Order;
use App\Entity\Position;
use App\Exception\InvalidAccountConfiguration;
use App\Helper\SerializerHelper;
use App\Helper\ValidationHelper;
use App\Service\JobService;
use App\Service\OrderService;
use App\Service\PositionService;
use App\Service\Ticker\TickerService;
use const DATE_ATOM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Generator;
use Predis\Client;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;

/**
 * Class AlpacaBrokerageService.
 */
class AlpacaBrokerageService extends AbstractBrokerageService
{
    protected const BROKERAGE_CONSTANTS = AlpacaConstants::class;

    private OrderService $orderService;
    private PositionService $positionService;
    private TickerService $tickerService;

    /**
     * AlpacaBrokerageService constructor.
     *
     * @param Client                 $cache
     * @param EntityManagerInterface $entityManager
     * @param BrokerageClient        $brokerageClient
     * @param JobService             $jobService
     * @param LoggerInterface        $logger
     * @param OrderService           $orderService
     * @param PositionService        $positionService
     * @param TickerService          $tickerService
     * @param ValidationHelper       $validator
     */
    public function __construct(
        Client $cache,
        EntityManagerInterface $entityManager,
        BrokerageClient $brokerageClient,
        JobService $jobService,
        LoggerInterface $logger,
        OrderService $orderService,
        PositionService $positionService,
        TickerService $tickerService,
        ValidationHelper $validator
    ) {
        $this->brokerageClient = $brokerageClient;
        $this->orderService = $orderService;
        $this->positionService = $positionService;
        $this->tickerService = $tickerService;
        $this->serializer = SerializerHelper::CamelCaseToSnakeCaseNormalizer();

        parent::__construct($brokerageClient, $cache, $entityManager, $jobService, $logger, $validator);
    }

    /**
     * @param BrokerageOrderInterface $alpacaOrder
     * @param Account                 $account
     *
     * @return Order
     */
    public function createOrder(BrokerageOrderInterface $alpacaOrder, Account $account): Order
    {
        $order = AlpacaOrderFactory::createOrder($alpacaOrder, $account);
        $this->orderService->prepareOrder($order, $alpacaOrder);
        $this->validator->validate($order);

        return $order;
    }

    /**
     * @param BrokerageOrderEventInterface $event
     * @param Account                      $account
     *
     * @return Order
     */
    public function createOrderFromEvent(BrokerageOrderEventInterface $event, Account $account): Order
    {
        $order = $this->createOrder($event->getOrder(), $account);
        $this->validator->validate($order);

        return $order;
    }

    /**
     * @param Account $account
     *
     * @throws ClientExceptionInterface
     * @throws InvalidAccountConfiguration
     *
     * @return AccountInterface|null
     */
    public function fetchAccountConfiguration(Account $account): ?AlpacaAccountConfiguration
    {
        $uri = $this->getUri(AlpacaConstants::ACCOUNT_CONFIG_ENDPOINT, $account);

        return $this->deserializeData(
            $this->sendRequest('GET', $uri, $account),
            AlpacaAccountConfiguration::class
        );
    }

    /**
     * @param AccountHistoryRequestInterface $request
     *
     * @throws InvalidAccountConfiguration|ClientExceptionInterface
     *
     * @return mixed
     */
    public function fetchAccountHistory(AccountHistoryRequestInterface $request): ?AccountHistoryInterface
    {
        $activity = $request->getParameter('activity_type');
        $baseUrl = sprintf('%s/activities/%s', AlpacaConstants::ACCOUNT_ENDPOINT, $activity);
        $uri = $this->getUri($baseUrl, $request->getAccount());
        $uri .= empty($params) ? '' : '?'.http_build_query($request->getParameters());
        $activities = $this->sendRequest('GET', $uri, $request->getAccount(), true);

        foreach ($activities as $key => &$activity) {
            if (\array_key_exists('type', $activity)) {
                $activity = $this->deserializeData($activity, AlpacaAccountTradeActivity::class);
            } else {
                $activity = $this->deserializeData($activity, AlpacaAccountNonTradeActivity::class);
            }
        }

        return $activities;
    }

    /**
     * @param Account $account
     *
     * @throws ClientExceptionInterface|InvalidAccountConfiguration
     *
     * @return AccountInterface|null
     */
    public function fetchAccount(Account $account): ?AccountInterface
    {
        return $this->deserializeData(
            $this->sendRequest('GET', AlpacaConstants::ACCOUNT_ENDPOINT, $account),
            AlpacaAccount::class
        );
    }

    /**
     * @param Account  $account
     * @param array    $params
     * @param int|null $limit
     *
     * @throws ClientExceptionInterface
     * @throws InvalidAccountConfiguration
     *
     * @return Generator
     */
    public function fetchOrderHistory(Account $account, array $params, ?int $limit = null): Generator
    {
        $count = 0;
        $params['direction'] = $params['direction'] ?? 'ASC';
        $params['limit'] = $params['limit'] ?? AlpacaConstants::ORDER_HISTORY_DEFAULT_PAGE_LIMIT;

        do {
            $alpacaOrders = [];
            $uri = $this->getUri(AlpacaConstants::ORDERS_ENDPOINT, $account);
            $uri .= empty($params) ? '' : '?'.http_build_query($params);
            $orderHistory = new ArrayCollection($this->sendRequest('GET', $uri, $account, true));
            $criteria = Criteria::create()->orderBy(['created_at' => Criteria::DESC]);
            $orderHistory = $orderHistory->matching($criteria);

            foreach ($orderHistory as $key => $orderData) {
                $alpacaOrders[] = $this->deserializeData($orderData, AlpacaConstants::ORDER_INFO_ENTITY_CLASS);
                ++$count;

                if (null !== $limit && $count >= $limit) {
                    break;
                }
            }
            $params['after'] = date_format($orderHistory->last()['created_at'], DATE_ATOM);

            yield $alpacaOrders;
        } while ($count >= $params['limit']);
    }

    /**
     * @param string  $symbol
     * @param Account $account
     *
     * @throws ClientExceptionInterface
     * @throws InvalidAccountConfiguration
     *
     * @return Position
     */
    public function fetchPosition(string $symbol, Account $account): Position
    {
        return $this->deserializeData(
            $this->sendRequest('GET', $this->getUri('positions/'.$symbol), $account),
            AlpacaPosition::class
        );
    }

    /**
     * @param Account $account
     *
     * @throws ClientExceptionInterface
     * @throws InvalidAccountConfiguration
     *
     * @return array|null
     */
    public function fetchPositions(Account $account): ?array
    {
        $positions = [];
        $uri = $this->getUri(AlpacaConstants::POSITIONS_ENDPOINT, $account);
        $data = $this->sendRequest('GET', $uri, $account, true);

        foreach ($data as $position) {
            $alpacaPosition = $this->deserializeData($position, AlpacaConstants::POSITION_INFO_ENTITY_CLASS);
            $positions[] = AlpacaPositionFactory::createPositionFromPositionInfo($alpacaPosition);
        }

        return $positions;
    }

    /**
     * @param Account $account
     *
     * @return array
     */
    protected function getRequestHeaders(Account $account): array
    {
        return [
            AlpacaConstants::REQUEST_HEADER_API_KEY => $account->getApiKey(),
            AlpacaConstants::REQUEST_HEADER_API_SECRET_KEY => $account->getApiSecret(),
        ];
    }

    /**
     * @param BrokerageOrderEventInterface $event
     * @param Order                        $order
     *
     * @return Order
     */
    public function updateOrderFromEvent(BrokerageOrderEventInterface $event, Order $order): Order
    {
        $order = AlpacaOrderFactory::updateOrder($event->getOrder(), $order);
        $this->orderService->getOrderStatusForOrder($order, $event->getEvent());

        return $order;
    }
}
