<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\AbstractEvent;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Protocol\MessageFactory;
use App\MessageClient\Protocol\Packet;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractMessageEventSubscriber extends AbstractEventSubscriber
{
    const ENTITY_LOG_FORMAT = 'json';

    use LoggerAwareTrait;
    use SerializerAwareTrait;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * @var ClientPublisher
     */
    protected $publisher;

    /**
     * AbstractMessageEventSubscriber constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     * @param MessageFactory           $messageFactory
     * @param ClientPublisher          $publisher
     * @param SerializerInterface      $serializer
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger,
        MessageFactory $messageFactory,
        ClientPublisher $publisher,
        SerializerInterface $serializer
    ) {
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
        $this->messageFactory = $messageFactory;
        $this->publisher = $publisher;
        $this->serializer = $serializer;
    }

    /**
     * @param AbstractEvent $event
     */
    public function dispatch(AbstractEvent $event)
    {
        $this->dispatcher->dispatch($event, $event::getEventName());
    }

    /**
     * @param Packet $packet
     *
     * @throws \App\MessageClient\Exception\PublishException
     */
    public function publish(Packet $packet)
    {
        $this->publisher->publish($packet);
    }
}
