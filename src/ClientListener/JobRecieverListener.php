<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\ClientListener;

use App\Constants\Transport\Queue;
use App\MessageClient\ClientListener\Channel;
use App\MessageClient\Protocol\Packet;
use App\Service\JobService;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use React\Promise as P;
use React\Promise\ExtendedPromiseInterface as Promise;

/**
 * Class JobRecieverListener.
 */
class JobRecieverListener extends AbstractCommandListener
{
    /** @var JobService */
    private $jobService;

    /**
     * JobRecieverListener constructor.
     *
     * @param JobService      $jobService
     * @param LoopInterface   $loop
     * @param LoggerInterface $logger
     */
    public function __construct(
        JobService $jobService,
        LoopInterface $loop,
        LoggerInterface $logger
    ) {
        $this->jobService = $jobService;
        parent::__construct($loop, $logger);
    }

    /**
     * @return array|mixed
     */
    public function getSubscribedTopics()
    {
        return [
            Queue::JOB_PERSISTENT_ROUTING_KEY,
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
            return P\resolve();
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
                        return $deferred->reject(new \Exception("process failed with code ($code)"));
                    }

                    return $deferred->resolve();
                }
                $deferred->reject(new \Exception("process qas terminated with signal ($term)"));
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
