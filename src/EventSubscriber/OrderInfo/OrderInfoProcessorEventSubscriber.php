<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber\OrderInfo;

use App\Constants\Transport\JobConstants;
use App\Event\Job\JobCompleteEvent;
use App\Event\Job\JobInitiatedEvent;
use App\Event\OrderInfo\OrderInfoProcessedEvent;
use App\Event\OrderInfo\OrderInfoProcessFailedEvent;
use App\Event\OrderInfo\OrderInfoReceivedEvent;
use App\EventSubscriber\AbstractMessageEventSubscriber;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Protocol\MessageFactory;
use App\Service\Message\OrderInfoMessageService;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class OrderInfoProcessorEventSubscriber.
 */
class OrderInfoProcessorEventSubscriber extends AbstractMessageEventSubscriber
{
    /**
     * @var OrderInfoMessageService
     */
    protected $orderInfoProcessorService;

    /**
     * OrderInfoProcessorEventSubscriber constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param MessageFactory           $messageFactory
     * @param LoggerInterface          $logger
     * @param OrderInfoMessageService  $orderProcessorService
     * @param ClientPublisher          $publisher
     * @param SerializerInterface      $serializer
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        MessageFactory $messageFactory,
        LoggerInterface $logger,
        OrderInfoMessageService $orderProcessorService,
        ClientPublisher $publisher,
        SerializerInterface $serializer
    ) {
        $this->orderInfoProcessorService = $orderProcessorService;
        parent::__construct($dispatcher, $logger, $messageFactory, $publisher, $serializer);
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
            $job = $event->getJob();
            $message = $event->getOrderInfoMessage();
            if (JobConstants::JOB_INITIATED === $job->getStatus()) {
                $this->dispatch(new JobInitiatedEvent($job));
            }
            $this->logger->info(sprintf('OrderInfo Message Received: %s ', $message['id']));
        } catch (\Exception $e) {
            $this->dispatcher->dispatch(
                new OrderInfoProcessFailedEvent(
                    $event->getOrderInfoMessage(),
                    $e,
                    $event->getJob()
                ),
                OrderInfoProcessFailedEvent::getEventName()
            );
            throw $e;
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
        $jobData = $job->getData();
        $orderInfo = $event->getOrderInfo();
        $jobData[$orderInfo->getId()] = JobConstants::JOB_PROCESSED;
        $job->setData($jobData);

        if (!\array_key_exists(JobConstants::JOB_PENDING, array_count_values($job->getData()))) {
            $this->dispatcher->dispatch(new JobCompleteEvent($job), JobCompleteEvent::getEventName());
        }
    }
}
