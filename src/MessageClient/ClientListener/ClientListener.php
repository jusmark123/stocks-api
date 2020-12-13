<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\ClientListener;

use App\MessageClient\Protocol\Packet;
use React\Promise\ExtendedPromiseInterface as Promise;

/**
 * Interface ClientListener.
 */
interface ClientListener
{
    const TAG = 'stocks-api.message-client.client-listener';

    /**
     * @return mixed
     */
    public function getSubscribedTopics();

    /**
     * @return mixed
     */
    public function getExchangeName();

    /**
     * @param Packet  $packet
     * @param Channel $channel
     *
     * @return Promise
     */
    public function consume(Packet $packet, Channel $channel): Promise;
}
