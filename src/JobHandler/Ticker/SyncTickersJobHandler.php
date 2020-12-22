<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\JobHandler\Ticker;

use App\Constants\Transport\JobConstants;
use App\Constants\Transport\Queue;
use App\Entity\Job;
use App\Entity\JobItem;
use App\Event\Job\JobInitiatedEvent;
use App\Event\Ticker\TickerProcessedEvent;
use App\Event\Ticker\TickerProcessFailedEvent;
use App\Event\Ticker\TickerPublishedEvent;
use App\Event\Ticker\TickerPublishFailedEvent;
use App\Exception\EmptyJobDataException;
use App\JobHandler\AbstractJobHandler;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Protocol\MessageFactory;
use App\Service\Brokerage\PolygonBrokerageService;
use App\Service\JobService;
use App\Service\TickerService;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class SyncTickersJobHandler.
 */
class SyncTickersJobHandler extends AbstractJobHandler
{
    const JOB_NAME = 'sync-tickers';
    const JOB_DESCRIPTION = 'Sync tickers from polygon.io';
    const HEADERS = [
        Queue::SYSTEM_PUBLISHER_HEADER_NAME => Queue::SYSTEM_PUBLISHER_NAME,
        JobConstants::REQUEST_HEADER_NAME => self::JOB_NAME,
    ];

    /**
     * @var PolygonBrokerageService
     */
    private $polygonService;

    /**
     * @var TickerService
     */
    private $tickerService;

    /**
     * SyncOrderHistoryJobHandler constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param JobService               $jobService
     * @param LoggerInterface          $logger
     * @param MessageFactory           $messageFactory
     * @param PolygonBrokerageService  $polygonService
     * @param ClientPublisher          $publisher
     * @param TickerService            $tickerService
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        JobService $jobService,
        LoggerInterface $logger,
        MessageFactory $messageFactory,
        PolygonBrokerageService $polygonService,
        ClientPublisher $publisher,
        TickerService $tickerService
    ) {
        $this->polygonService = $polygonService;
        $this->tickerService = $tickerService;
        parent::__construct($dispatcher, $jobService, $logger, $messageFactory, $publisher);
    }

    /**
     * @param string      $jobName
     * @param string|null $resourceClass
     *
     * @return bool
     */
    public function supports(string $jobName, ?string $resourceClass = null): bool
    {
        return false;
    }

    /**
     * @param Job $job
     *
     * @throws EmptyJobDataException
     *
     * @return bool|mixed
     */
    public function prepare(Job $job): bool
    {
        $this->dispatcher->dispatch(new JobInitiatedEvent($job));
        $jobItems = $job->getJobItems();

        if (null === $jobItems || empty($jobItems)) {
            throw new EmptyJobDataException();
        }

        foreach ($jobItems as $jobItem) {
            try {
                $packet = $this->messageFactory->createPacket(
                    Queue::TICKERS_PERSISTENT_ROUTING_KEY,
                    serialize($jobItem),
                    self::HEADERS
                );
                $this->publisher->publish($packet);
                $this->dispatcher->dispatch(new TickerPublishedEvent($jobItem));
            } catch (\Throwable $e) {
                $this->dispatcher->dispatch(
                    new TickerPublishFailedEvent(
                        $e,
                        $jobItem
                    ),
                    TickerPublishedEvent::getEventName()
                );
            }
        }

        return true;
    }

    /**
     * @param JobItem $jobItem
     * @param Job     $job
     *
     * @return bool
     */
    public function execute(JobItem $jobItem, Job $job): bool
    {
        $ticker = null;
        $tickerInfo = null;
        try {
            $this->jobItemProcessing($jobItem);

            $ticker = $this->tickerService->createTickerFromMessage($job->getAccount(), $jobItem->getData());

            $this->dispatcher->dispatch(
                new TickerProcessedEvent($jobItem, $ticker, $tickerInfo),
                TickerProcessedEvent::getEventName()
            );
        } catch (\Exception $e) {
            $this->dispatcher->dispatch(
                new TickerProcessFailedEvent(
                    $e,
                    $jobItem,
                    $ticker,
                    $tickerInfo
                ),
                TickerProcessedEvent::getEventName()
            );
        }

        return true;
    }
}
