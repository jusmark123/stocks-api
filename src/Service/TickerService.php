<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\DTO\Brokerage\TickerInterface;
use App\Entity\Account;
use App\MessageClient\Exception\InvalidMessage;
use App\MessageClient\Exception\PublishException;
use App\Service\Brokerage\PolygonBrokerageService;
use App\Service\Entity\TickerEntityService;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;

/**
 * Class TickerService.
 */
class TickerService extends AbstractService
{
    /**
     * @var PolygonBrokerageService
     */
    private $polygonBrokerageService;

    /**
     * @var TickerEntityService
     */
    private $tickerEntityService;

    /**
     * TickerService constructor.
     *
     * @param LoggerInterface         $logger
     * @param PolygonBrokerageService $polygonBrokerageService
     * @param TickerEntityService     $tickerEntityService
     */
    public function __construct(
        LoggerInterface $logger,
        PolygonBrokerageService $polygonBrokerageService,
        TickerEntityService $tickerEntityService
    ) {
        $this->polygonBrokerageService = $polygonBrokerageService;
        $this->tickerEntityService = $tickerEntityService;
        parent::__construct($logger);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function syncTickerTypes(): void
    {
        $this->polygonBrokerageService->syncTickerTypes();
    }

    /**
     * @param Account $account
     *
     * @throws ClientExceptionInterface
     * @throws InvalidMessage
     * @throws PublishException
     */
    public function syncTickers(Account $account): void
    {
        $this->polygonBrokerageService->syncTickers($account);
    }

    /**
     * @param Account $account
     * @param array   $message
     */
    public function createTickerFromMessage(Account $account, array $message)
    {
        $this->polygonBrokerageService->createTickerFromMessage($account, $message);
    }

    /**
     * @param Account         $account
     * @param TickerInterface $tickerInfo
     */
    public function createTickerFromTickerInfo(Account $account, TickerInterface $tickerInfo)
    {
        $this->polygonBrokerageService->createTickerFromTickerInfo($tickerInfo, $account);
    }
}
