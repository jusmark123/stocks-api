<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Handler;

use App\Entity\Job;
use App\Message\Factory\SyncOrderHistoryMessageFactory;
use App\Message\Job\Handler\AbstractJobMessageHandler;
use App\Message\SyncOrdersRequestMessage;
use App\Service\JobService;
use App\Service\OrderService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class SyncOrdersRequestMessageHandler.
 */
class SyncOrdersRequestMessageHandler extends AbstractJobMessageHandler
{
    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * SyncOrdersRequestMessageHandler constructor.
     *
     * @param Client                 $cache
     * @param EntityManagerInterface $entityManager
     * @param MessageBusInterface    $messageBus
     * @param JobService             $jobService
     * @param LoggerInterface        $logger
     * @param OrderService           $orderService
     * @param UserService            $userService
     */
    public function __construct(
        Client $cache,
        EntityManagerInterface $entityManager,
        MessageBusInterface $messageBus,
        JobService $jobService,
        LoggerInterface $logger,
        OrderService $orderService,
        UserService $userService
    ) {
        $this->orderService = $orderService;
        parent::__construct($cache, $entityManager, $messageBus, $jobService, $logger, $userService);
    }

    /**
     * @param SyncOrdersRequestMessage $requestMessage
     *
     * @return Job
     */
    public function __invoke(SyncOrdersRequestMessage $requestMessage): Job
    {
        return $this->parseJobRequest($requestMessage, [$this->orderService, 'fetchOrderHistory'],
            SyncOrderHistoryMessageFactory::class);
    }
}
