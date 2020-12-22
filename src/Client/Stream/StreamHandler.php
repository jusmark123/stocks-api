<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Client\Stream;

use App\Client\Stream\Protocol\Stream;
use App\Client\Stream\Protocol\StreamPacket;

interface StreamHandler
{
    /**
     * @return mixed
     */
    public function getSubscribedStreams();

    /**
     * @param StreamPacket $packet
     * @param Stream       $stream
     *
     * @return mixed
     */
    public function consume(StreamPacket $packet, Stream $stream);

    public function connect(Stream $stream);
}
