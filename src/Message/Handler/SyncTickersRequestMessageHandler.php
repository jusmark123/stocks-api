<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Handler;

use App\Entity\Job;
use App\Message\Job\Handler\AbstractJobMessageHandler;
use App\Message\SyncTickersRequestMessage;
use App\Service\JobService;
use App\Service\Ticker\TickerService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class SyncTickersRequestMessageHandler.
 */
class SyncTickersRequestMessageHandler extends AbstractJobMessageHandler
{
    /**
     * @var TickerService
     */
    private TickerService $tickerService;

    /**
     * SyncOrdersRequestMessageHandler constructor.
     *
     * @param Client                 $cache
     * @param EntityManagerInterface $entityManager
     * @param MessageBusInterface    $messageBus
     * @param JobService             $jobService
     * @param LoggerInterface        $logger
     * @param TickerService          $tickerService
     * @param UserService            $userService
     */
    public function __construct(
        Client $cache,
        EntityManagerInterface $entityManager,
        MessageBusInterface $messageBus,
        JobService $jobService,
        LoggerInterface $logger,
        TickerService $tickerService,
        UserService $userService
    ) {
        $this->tickerService = $tickerService;
        parent::__construct($cache, $entityManager, $messageBus, $jobService, $logger, $userService);
    }

    /**
     * @param SyncTickersRequestMessage $requestMessage
     *
     * @return Job
     */
    public function __invoke(SyncTickersRequestMessage $requestMessage): Job
    {
        return $this->parseJobRequest($requestMessage, [$this->tickerService, 'fetchTickers']);
    }
}
