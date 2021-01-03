<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Receiver;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Receiver\ReceiverInterface;

/**
 * Class SyncOrderHistoryReceiver.
 */
class SyncOrderHistoryReceiver implements ReceiverInterface
{
    public function get(): iterable
    {
        // TODO: Implement get() method.
    }

    public function ack(Envelope $envelope): void
    {
        // TODO: Implement ack() method.
    }

    public function reject(Envelope $envelope): void
    {
        // TODO: Implement reject() method.
    }
}
