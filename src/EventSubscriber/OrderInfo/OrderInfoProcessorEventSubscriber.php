<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber\OrderInfo;

use App\Event\OrderInfo\OrderInfoReceivedEvent;
use App\EventSubscriber\AbstractMessageEventSubscriber;
use App\Service\Message\OrderInfoProcessorService;
use Psr\Log\LoggerInterface;
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
     * @param SerializerInterface       $serializer
     * @param LoggerInterface           $logger
     * @param OrderInfoProcessorService $orderProcessorService
     */
    public function __construct(
        SerializerInterface $serializer,
        LoggerInterface $logger,
        OrderInfoProcessorService $orderProcessorService
    ) {
        $this->orderInfoProcessorService = $orderProcessorService;
        parent::__construct($logger, $serializer);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OrderInfoReceivedEvent::getEventName() => ['process'],
        ];
    }

    /**
     * @param OrderInfoReceivedEvent $event
     *
     * @return [type]
     */
    public function process(OrderInfoReceivedEvent $event)
    {
        $this->orderInfoProcessorService->process($event->getOrderInfoMessage());
    }
}
