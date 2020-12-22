<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Bridge\Symfony\Messenger\MessageDispatcher;

abstract class AbstractMessageBusDispatcher implements MessageBusDispatcherAwareInterface
{
    use MessageBusDispatcherTrait;
}
