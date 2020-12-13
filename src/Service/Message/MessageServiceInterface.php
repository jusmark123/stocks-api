<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Message;

use App\MessageClient\Protocol\Packet;
use React\Promise\ExtendedPromiseInterface;

/**
 * Interface MessageServiceInterface.
 */
interface MessageServiceInterface
{
    /**
     * @param Packet $packet
     *
     * @return mixed
     */
    public function receive(Packet $packet);
}
