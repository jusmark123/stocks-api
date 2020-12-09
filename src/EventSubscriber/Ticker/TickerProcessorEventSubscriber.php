<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber\Ticker;

use App\Event\Ticker\TickerProcessedEvent;
use App\Event\Ticker\TickerPublishFailedEvent;
use App\Event\Ticker\TickerReceivedEvent;
use App\Event\Ticker\TickerReceiveFailedEvent;
use App\EventSubscriber\AbstractMessageEventSubscriber;
use App\Service\Entity\TickerEntityService;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class TickerProcessorEventSubscriber.
 */
class TickerProcessorEventSubscriber extends AbstractMessageEventSubscriber
{
    /**
     * @var TickerEntityService
     */
    protected $tickerService;

    /**
     * TickerProcessorEventSubscriber constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param SerializerInterface      $serializer
     * @param LoggerInterface          $logger
     * @param TickerEntityService      $tickerService
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        SerializerInterface $serializer,
        LoggerInterface $logger,
        TickerEntityService $tickerService
    ) {
        $this->tickerService = $tickerService;
        parent::__construct($dispatcher, $logger, $serializer);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            TickerReceivedEvent::getEventName() => [
                [
                    'receive',
                ],
            ],
            TickerProcessedEvent::getEventName() => [
                [
                    'processed',
                ],
            ],
        ];
    }

    /**
     * @param TickerReceivedEvent $event
     */
    public function receive(TickerReceivedEvent $event)
    {
        try {
            $tickerMessage = $event->getTickerMessage();
            $this->logger->info('Ticker Message Received', $tickerMessage);
            $ticker = $this->tickerService->createTickerFromMessage($tickerMessage);
            $this->dispatcher->dispatch(
                new TickerProcessedEvent($ticker),
                TickerPublishFailedEvent::getEventName()
            );
        } catch (\Exception $e) {
            $this->dispatcher->dispatch(
                new TickerReceiveFailedEvent($tickerMessage, $e),
                TickerReceiveFailedEvent::getEventName()
            );
        }
    }

    /**
     * @param TickerProcessedEvent $event
     */
    public function processed(TickerProcessedEvent $event)
    {
        $this->logger->info(sprintf('Processed ticker for %s', $event->getTicker()->getName()));
    }
}
