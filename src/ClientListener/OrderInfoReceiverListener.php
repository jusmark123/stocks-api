<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\ClientListener;

use ApiPlatform\Core\Exception\ItemNotFoundException;
use App\Constants\Transport\JobConstants;
use App\Constants\Transport\Queue;
use App\Entity\Job;
use App\Entity\Manager\JobEntityManager;
use App\Event\Job\JobReceivedEvent;
use App\Event\OrderInfo\OrderInfoReceiveFailedEvent;
use App\MessageClient\ClientListener\Channel;
use App\MessageClient\Exception\InvalidPacket;
use App\MessageClient\Protocol\Packet;
use App\Service\Message\OrderInfoProcessorService;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use React\Promise as P;
use React\Promise\ExtendedPromiseInterface as Promise;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class OrderInfoReceiverListener.
 */
class OrderInfoReceiverListener extends AbstractCommandListener
{
    const JOB_NOT_FOUND = 'Job not found';

    /** @var EventDispatcherInterface */
    private $dispatcher;

    /** @var JobEntityManager */
    private $entityManager;

    /** @var OrderInfoProcessorService */
    private $orderInfoProcessorService;

    /**
     * OrderInfoReceiverListener constructor.
     *
     * @param EventDispatcherInterface  $dispatcher
     * @param JobEntityManager          $entityManager
     * @param OrderInfoProcessorService $orderInfoProcessorService
     * @param LoopInterface             $loop
     * @param LoggerInterface           $logger
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        JobEntityManager $entityManager,
        OrderInfoProcessorService $orderInfoProcessorService,
        LoopInterface $loop,
        LoggerInterface $logger
    ) {
        $this->dispatcher = $dispatcher;
        $this->entityManager = $entityManager;
        $this->orderInfoProcessorService = $orderInfoProcessorService;
        parent::__construct($loop, $logger);
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
            $job = null;
            try {
                if (!$packet->hasHeader(JobConstants::JOB_ID_HEADER_NAME)) {
                    throw new InvalidPacket();
                }

                $jobId = $packet->getHeader(JobConstants::JOB_ID_HEADER_NAME);
                $job = $this->entityManager->findOneBy(['guid' => $jobId]);

                if (!$job instanceof Job) {
                    throw new ItemNotFoundException(self::JOB_NOT_FOUND);
                }

                if (JobConstants::JOB_INITIATED === $job->getStatus()) {
                    $this->dispatcher->dispatch(
                        new JobReceivedEvent($job),
                        JobReceivedEvent::getEventName()
                    );
                }
                $this->orderInfoProcessorService->process(json_decode($packet->getMessage(), true));

                return P\resolve();
            } catch (\Exception $e) {
                $this->dispatcher->dispatch(
                    new OrderInfoReceiveFailedEvent(json_decode($packet->getMessage(), true), $e, $job),
                    OrderInfoReceiveFailedEvent::getEventName()
                );

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