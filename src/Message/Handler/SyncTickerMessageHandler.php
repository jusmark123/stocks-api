<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Handler;

use App\Entity\Job;
use App\Entity\JobItem;
use App\Entity\Ticker;
use App\Exception\MessageProcessedException;
use App\Message\Job\Handler\AbstractJobMessageHandler;
use App\Message\SyncTickerMessage;
use App\Service\JobService;
use App\Service\Ticker\TickerService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class SyncTickerMessageHandler.
 */
class SyncTickerMessageHandler extends AbstractJobMessageHandler
{
    /**
     * @var TickerService
     */
    private $tickerService;

    /**
     * SyncTickerMessageHandler constructor.
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
     * @param SyncTickerMessage $message
     *
     * @return Ticker|null
     */
    public function __invoke(SyncTickerMessage $message): ?JobItem
    {
        $job = null;
        $jobItem = null;
        $ticker = null;
        $tickerInfo = null;

        return $this->parseJobItemRequest($message, [$this->tickerService, 'syncTicker'],
            [$this, 'fetchTicker']);
    }

    /**
     * @param JobItem  $jobItem
     * @param Job|null $job
     *
     * @throws MessageProcessedException
     */
    public function fetchTicker(JobItem $jobItem, ?Job $job = null)
    {
        $brokerage = $jobItem->getJob()->getAccount()->getBrokerage();

        /** @var Ticker $ticker */
        $ticker = $this->entityManager
            ->getRepository(Ticker::class)
            ->findOneBy(['ticker' => $jobItem->getData()['ticker']]);

        if ($ticker instanceof Ticker) {
            if (!$ticker->getBrokerages()->contains($brokerage)) {
                $ticker->addBrokerage($brokerage);
                $this->entityManager->persist($ticker);
            }

            throw new MessageProcessedException();
        }
    }
}
