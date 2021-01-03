<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Client\BrokerageClient;
use App\Entity\Job;
use App\Entity\Ticker;
use App\Helper\ValidationHelper;
use App\Service\Brokerage\BrokerageServiceProvider;
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
     * @var DefaultTypeService
     */
    private $defaultTypeService;

    /**
     * @var BrokerageServiceProvider
     */
    private $brokerageServiceProvider;

    /**
     * @var BrokerageClient
     */
    private $brokerageClient;

    /**
     * TickerService constructor.
     *
     * @param BrokerageClient          $brokerageClient
     * @param BrokerageServiceProvider $brokerageServiceProvider
     * @param DefaultTypeService       $defaultTypeService
     * @param LoggerInterface          $logger
     * @param PolygonBrokerageService  $polygonBrokerageService
     * @param TickerEntityService      $tickerEntityService
     * @param ValidationHelper         $validator
     */
    public function __construct(
        BrokerageClient $brokerageClient,
        BrokerageServiceProvider $brokerageServiceProvider,
        DefaultTypeService $defaultTypeService,
        LoggerInterface $logger,
        PolygonBrokerageService $polygonBrokerageService,
        TickerEntityService $tickerEntityService,
        ValidationHelper $validator
    ) {
        $this->brokerageClient = $brokerageClient;
        $this->brokerageServiceProvider = $brokerageServiceProvider;
        $this->defaultTypeService = $defaultTypeService;
        $this->tickerEntityService = $tickerEntityService;
        $this->polygonBrokerageService = $polygonBrokerageService;
        $this->validator = $validator;
        parent::__construct($logger);
    }

    public function syncTickerTypes(): void
    {
        $this->polygonBrokerageService->syncTickerTypes();
    }

    /**
     * @param array $tickerData
     * @param Job   $job
     *
     * @return Ticker|null
     */
    public function syncTicker(array $tickerData, Job $job): ?Ticker
    {
        $account = $job->getAccount();
        $brokerageService = $this->brokerageServiceProvider->getBrokerageService($account->getBrokerage());
        $tickerInfo = $brokerageService->createTickerInfoFromMessage($tickerData);
        $ticker = $brokerageService->createTickerFromTickerInfo($tickerInfo, $job);
        $this->tickerEntityService->save($ticker);

        return $ticker;
    }
}
