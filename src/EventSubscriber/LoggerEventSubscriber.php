<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\LoggerEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class LoggerEventSubscriber.
 */
class LoggerEventSubscriber extends AbstractEventSubscriber
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * LoggerEventSubscriber constructor.
     *
     * @param LoggerInterface          $logger
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(LoggerInterface $logger, EventDispatcherInterface $dispatcher)
    {
        $this->logger = $logger;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return \string[][][]
     */
    public static function getSubscribedEvents()
    {
        return [
            'console.command' => [
                [
                    'setupDebugLogging',
                ],
            ],
            LoggerEvent::getInfoEventName() => [
                [
                    'info',
                ],
            ],
        ];
    }

    public function setupDebugLogging()
    {
        foreach ($this->dispatcher->getListeners() as $key => $listener) {
            $this->dispatcher->addListener($key, $this->logEvent($key, 'pre'), 2048);
            $this->dispatcher->addListener($key, $this->logEvent($key, 'post'), -2048);
        }
    }

    /**
     * @param $eventName
     * @param $prePost
     *
     * @return \Closure
     */
    public function logEvent($eventName, $prePost)
    {
        return function ($event) use ($eventName, $prePost) {
            $this->logger->debug("{$eventName}.{$prePost}", [
                'when' => $prePost,
                'eventName' => $eventName,
                'event' => $event,
            ]);
        };
    }

    /**
     * @param LoggerEvent $event
     */
    public function info(LoggerEvent $event)
    {
        $this->logger->info($event->getMessage());
    }
}
