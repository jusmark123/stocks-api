<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber\OrderInfo;

use App\Constants\Transport\JobConstants;
use App\Event\Job\JobCompleteEvent;
use App\Event\OrderInfo\OrderInfoProcessedEvent;
use App\Event\OrderInfo\OrderInfoProcessFailedEvent;
use App\Event\OrderInfo\OrderInfoReceivedEvent;
use App\EventSubscriber\AbstractMessageEventSubscriber;
use App\Service\Message\OrderInfoProcessorService;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class OrderInfoProcessorEventSubscriber.
 */
class OrderInfoProcessorEventSubscriber extends AbstractMessageEventSubscriber
{
    /**
     * @var OrderInfoProcessorService
     */
    protected $orderInfoProcessorService;

    /**
     * OrderInfoProcessorEventSubscriber constructor.
     *
     * @param EventDispatcherInterface  $dispatcher
     * @param SerializerInterface       $serializer
     * @param LoggerInterface           $logger
     * @param OrderInfoProcessorService $orderProcessorService
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        SerializerInterface $serializer,
        LoggerInterface $logger,
        OrderInfoProcessorService $orderProcessorService
    ) {
        $this->orderInfoProcessorService = $orderProcessorService;
        parent::__construct($dispatcher, $logger, $serializer);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OrderInfoReceivedEvent::getEventName() => [
                [
                    'receive',
                ],
            ],
            OrderInfoProcessedEvent::getEventName() => [
                [
                    'processed',
                ],
            ],
        ];
    }

    /**
     * @param OrderInfoReceivedEvent $event
     */
    public function receive(OrderInfoReceivedEvent $event)
    {
        try {
            $this->orderInfoProcessorService->process($event);
        } catch (\Exception $e) {
            $this->dispatcher->dispatch(
                new OrderInfoProcessFailedEvent(
                    $event->getOrderInfoMessage(),
                    $e,
                    $event->getJob()
                ),
                OrderInfoProcessFailedEvent::getEventName()
            );
        }
    }

    /**
     * @param OrderInfoProcessedEvent $event
     *
     * @throws \Exception
     */
    public function processed(OrderInfoProcessedEvent $event)
    {
        $job = $event->getJob();
        if (!\array_key_exists(JobConstants::JOB_PENDING, array_count_values($job->getData()))) {
            $this->dispatcher->dispatch(
                new JobCompleteEvent($job),
                JobCompleteEvent::getEventName()
            );
        }
    }
}
