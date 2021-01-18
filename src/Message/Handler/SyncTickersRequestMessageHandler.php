<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Handler;

use App\Entity\Job;
use App\Exception\JobCancelledException;
use App\Message\Job\Handler\AbstractJobMessageHandler;
use App\Message\SyncTickersRequestMessage;
use App\Service\Brokerage\PolygonBrokerageService;
use App\Service\JobService;
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
     * @var PolygonBrokerageService
     */
    private $polygonService;

    /**
     * SyncOrdersRequestMessageHandler constructor.
     *
     * @param Client                  $cache
     * @param EntityManagerInterface  $entityManager
     * @param MessageBusInterface     $messageBus
     * @param JobService              $jobService
     * @param LoggerInterface         $logger
     * @param PolygonBrokerageService $polygonService
     * @param UserService             $userService
     */
    public function __construct(
        Client $cache,
        EntityManagerInterface $entityManager,
        MessageBusInterface $messageBus,
        JobService $jobService,
        LoggerInterface $logger,
        PolygonBrokerageService $polygonService,
        UserService $userService
    ) {
        $this->polygonService = $polygonService;
        parent::__construct($cache, $entityManager, $messageBus, $jobService, $logger, $userService);
    }

    /**
     * @param SyncTickersRequestMessage $requestMessage
     *
     * @throws JobCancelledException
     *
     * @return Job
     */
    public function __invoke(SyncTickersRequestMessage $requestMessage): Job
    {
        return $this->parseJobRequest($requestMessage, [$this->polygonService, 'fetchTickers']);
    }
}
