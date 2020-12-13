<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\ClientListener;

use App\Constants\Transport\Queue;
use App\MessageClient\ClientListener\Channel;
use App\MessageClient\Protocol\Packet;
use App\Service\Message\OrderInfoMessageService;
use Exception;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use React\Promise as P;
use React\Promise\ExtendedPromiseInterface as Promise;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class OrderInfoTopicReceiverListener.
 */
class OrderInfoTopicReceiverListener extends AbstractCommandListener
{
    const EXCHANGE_NAME = Queue::TOPIC_EXCHANGE;

    /**
     * @var OrderInfoMessageService
     */
    private $orderInfoMessageService;

    /**
     * OrderInfoTopicReceiverListener constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param OrderInfoMessageService  $orderInfoMessageService
     * @param LoopInterface            $loop
     * @param LoggerInterface          $logger
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        OrderInfoMessageService $orderInfoMessageService,
        LoopInterface $loop,
        LoggerInterface $logger
    ) {
        $this->orderInfoMessageService = $orderInfoMessageService;
        parent::__construct($dispatcher, $loop, $logger);
    }

    /**
     * @return array|mixed
     */
    public function getSubscribedTopics()
    {
        return [
            Queue::ORDER_INFO_PERSISTENT_ROUTING_KEY,
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
                $this->orderInfoMessageService->receive($packet);

                return P\resolve();
            } catch (Exception $e) {
                return P\reject($e);
            }
        }

        try {
            $message = $packet->getMessage();

            $deferred = new P\Deferred();

            $command = implode(' ', [
                './bin/console',
            ]);

            $proc = $this->getProcess($command);

            $proc->on('exit', function ($code, $term) use ($deferred) {
                if (null === $term) {
                    if ($code) {
                        return $deferred->reject(new Exception("process failed with code ($code)"));
                    }

                    return $deferred->resolve();
                }
                $deferred->reject(new Exception("process qas terminated with signal ($term)"));
            });

            $proc->start($this->loop);

            $proc->stdout->on('data', function ($chunk) {
                $this->logger->debug("rom child proc ($chunk)");
            });

            $proc->stdin->end(json_encode($message));
        } catch (\Throwable $throwable) {
            return P\reject($throwable);
        }
    }
}
