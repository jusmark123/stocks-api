<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\ClientListener;

use App\Event\AbstractEvent;
use App\MessageClient\ClientListener\ClientListener;
use Psr\Log\LoggerInterface;
use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class AbstractCommandListener.
 */
abstract class AbstractCommandListener implements ClientListener
{
    protected const EXCHANGE_NAME = '';

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var LoopInterface
     */
    protected $loop;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * AbstractCommandListener constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param LoopInterface            $loop
     * @param LoggerInterface          $logger
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        LoopInterface $loop,
        LoggerInterface $logger
    ) {
        $this->dispatcher = $dispatcher;
        $this->loop = $loop;
        $this->logger = $logger;
    }

    /**
     * @param string $command
     *
     * @return Process
     */
    protected function getProcess(string $command): Process
    {
        $env = getenv();
        $env['APP_ENV'] = empty($env['APP_ENV_QUEUE_CONSOLE_COMMAND']) ? 'prod' : $env['APP_ENV_QUEUE_CONSOLE_COMMAND'];

        return new Process($command, null, $env);
    }

    /**
     * @return string
     */
    public function getExchangeName(): string
    {
        return self::EXCHANGE_NAME;
    }

    /**
     * @param AbstractEvent $event
     */
    protected function dispatch(AbstractEvent $event)
    {
        $this->dispatcher->dispatch($event, $event::getEventName());
    }
}
