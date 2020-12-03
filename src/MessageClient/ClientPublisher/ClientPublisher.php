<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\ClientPublisher;

use App\MessageClient\Exception\PublishException;
use App\MessageClient\Protocol\Packet;

interface ClientPublisher
{
    /**
     * @param Packet $packet
     *
     * @throws PublishException
     *
     * @return bool|int|\React\Promise\PromiseInterface
     */
    public function publish(Packet $packet);
}
