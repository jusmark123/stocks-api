<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\ClientListener;

use App\Constants\Transport\Queue;
use App\Event\Ticker\TickerPublishFailedEvent;
use App\MessageClient\ClientListener\Channel;
use App\MessageClient\Protocol\Packet;
use App\Service\Entity\TickerEntityService;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use React\Promise as P;
use React\Promise\ExtendedPromiseInterface as Promise;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class TickerReceiverListener.
 */
class TickerReceiverListener extends AbstractCommandListener
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var TickerEntityService
     */
    private $tickerService;

    /**
     * TickerReceiverListener constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     * @param LoopInterface            $loop
     * @param TickerEntityService      $tickerService
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger,
        LoopInterface $loop,
        TickerEntityService $tickerService
    ) {
        $this->dispatcher = $dispatcher;
        $this->tickerService = $tickerService;
        parent::__construct($loop, $logger);
    }

    /**
     * @return array
     */
    public function getSubscribedTopics()
    {
        return [
            Queue::TICKERS_PERSISTENT_ROUTING_KEY,
        ];
    }

    /**
     * @param Packet  $packet
     * @param Channel $channel
     *
     * @return Promise
     */
    public function consume(Packet $packet, Channel $channel): Promise
    {
        if ($packet->hasHeader(Queue::SYSTEM_PUBLISHER_HEADER_NAME)
            && Queue::SYSTEM_PUBLISHER_NAME === $packet->getHeader(Queue::SYSTEM_PUBLISHER_HEADER_NAME)
        ) {
            try {
                $this->tickerService->createTickerFromMessage(json_decode($packet->getMessage(), true));

                return P\resolve();
            } catch (\Exception $e) {
                $this->dispatcher->dispatch(
                    new TickerPublishFailedEvent(json_decode($packet->getMessage(), true), $e),
                    TickerPublishFailedEvent::getEventName()
                );

                return P\reject($e);
            }
        }
    }
}
