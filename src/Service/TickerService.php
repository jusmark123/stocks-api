<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Service\Brokerage\PolygonBrokerageService;
use App\Service\Entity\TickerEntityService;
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
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function syncTickerTypes(): void
    {
        $this->polygonBrokerageService->syncTickerTypes();
    }

    /**
     * @throws \App\MessageClient\Exception\InvalidMessage
     * @throws \App\MessageClient\Exception\PublishException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function syncTickers(): void
    {
        $this->polygonBrokerageService->syncTickers();
    }
}
