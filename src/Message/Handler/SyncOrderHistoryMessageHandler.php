<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Handler;

use ApiPlatform\Core\Exception\InvalidValueException;
use App\Entity\Job;
use App\Entity\JobItem;
use App\Entity\Order;
use App\Exception\MessageProcessedException;
use App\Message\Job\Handler\AbstractJobMessageHandler;
use App\Message\SyncOrderHistoryMessage;
use App\Service\JobService;
use App\Service\OrderService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class SyncOrderHistoryMessageHandler.
 */
class SyncOrderHistoryMessageHandler extends AbstractJobMessageHandler
{
    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * SyncOrderHistoryMessageHandler constructor.
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
     * @param SyncOrderHistoryMessage $message
     *
     * @return JobItem
     */
    public function __invoke(SyncOrderHistoryMessage $message): ?JobItem
    {
        return $this->parseJobItemRequest($message, [$this->orderService, 'syncOrderHistory'],
            [$this, 'fetchOrder']);
    }

    /**
     * @param JobItem $jobItem
     * @param Job     $job
     *
     * @throws MessageProcessedException
     */
    public function fetchOrder(JobItem $jobItem, Job $job)
    {
        $uniqueKey = $this->orderService->getBrokerageUniqueOrderKey($job->getAccount());
        $data = $jobItem->getData();

        if (!\array_key_exists($uniqueKey, $data)) {
            throw new InvalidValueException(
                sprintf('Unique Key %s not found in JobItem Data %s', $uniqueKey, self::class));
        }

        /* @var Order $order */
        $order = $this->entityManager
            ->getRepository(Order::class)
            ->findOneBy([
                'account' => $job->getAccount(),
                'guid' => $data[$uniqueKey],
            ]);

        if ($order instanceof Order) {
            throw new MessageProcessedException();
        }
    }
}
