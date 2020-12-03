<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Bridge\Symfony\Messenger\MessageDispatcher;

use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Trait MessageBusDispatcherTrait.
 */
trait MessageBusDispatcherTrait
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * MessageBusDispatcherTrait constructor.
     *
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @return MessageBusInterface
     */
    public function getMessageBus(): MessageBusInterface
    {
        return $this->messageBus;
    }

    /**
     * @param $message
     * @param array $stamps
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function dispatch($message, array $stamps = [])
    {
        if (!$this->isHandlerFailedExceptionDefined()) {
            return $this->getMessageBus()->dispatch($message, $stamps);
        }

        try {
            return $this->getMessageBus()->dispatch($message, $stamps);
        } catch (HandlerFailedException $e) {
            while ($e instanceof HandlerFailedException) {
                $e = $e->getPrevious();
            }
            throw $e;
        }
    }

    /**
     * @return bool
     */
    protected function isHandlerFailedExceptionDefined()
    {
        return class_exists(HandlerFailedException::class);
    }
}
