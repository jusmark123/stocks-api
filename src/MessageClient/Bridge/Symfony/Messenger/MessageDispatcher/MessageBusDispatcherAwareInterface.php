<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Bridge\Symfony\Messenger\MessageDispatcher;

use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Interface MessageBusDispatcherAwareInterface.
 */
interface MessageBusDispatcherAwareInterface extends MessageDispatcherInterface
{
    /**
     * @return MessageBusInterface
     */
    public function getMessageBus(): MessageBusInterface;

    /**
     * @param $message
     * @param array $stamps
     *
     * @return mixed
     */
    public function dispatch($message, array $stamps = []);
}
