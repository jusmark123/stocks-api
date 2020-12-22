<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\ClientListener;

use App\MessageClient\Exception\Exception;
use App\MessageClient\Protocol\Packet;
use Bunny\Channel as RabbitBunnyChannel;
use Bunny\Message;
use React\Promise\ExtendedPromiseInterface as Promise;
use function React\Promise\reject;
use function React\Promise\resolve;

class BunnyChannel implements Channel
{
    protected $channel;

    protected $exchangeName;

    public function __construct(
        RabbitBunnyChannel $channel,
        string $exchangeName
    ) {
        $this->channel = $channel;
        $this->exchangeName = $exchangeName;
    }

    /**
     * @param Packet $packet
     *
     * @return Promise
     */
    public function publish(Packet $packet): Promise
    {
        $promise = $this->channel->publish(
            json_encode($packet),
            [],
            $this->exchangeName,
            $packet->getTopic()
        );

        return $promise;
    }

    protected function getMessageFromPacket($packet): Promise
    {
        if ($packet instanceof Message) {
            return resolve($packet);
        }

        if ($packet instanceof Packet) {
            return resolve($packet->getOriginalMessage());
        }

        return reject(new Exception('invalid packet'));
    }

    public function ack($packet, bool $multiple = false): Promise
    {
        $promise = $this->getMessageFromPacket($packet)->then(
            function (Message $message) use ($multiple) {
                return $this->channel->ack($message, $multiple);
            });

        return $promise;
    }

    public function nack($packet, bool $multiple = false, bool $requeue = true): Promise
    {
        $promise = $this->getMessageFromPacket($packet)->then(
            function (Message $message) use ($multiple, $requeue) {
                return $this->channel->nack($message, $multiple, $requeue);
            });

        return $promise;
    }

    public function reject($packet, bool $requeue = true): Promise
    {
        $promise = $this->getMessageFromPacket($packet)->then(
            function (Message $message) use ($requeue) {
                return $this->channel->reject($message, $requeue);
            });

        return $promise;
    }

    public function get()
    {
        $this->channel->get();
    }
}
