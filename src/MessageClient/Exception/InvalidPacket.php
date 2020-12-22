<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Exception;

class InvalidPacket extends Exception
{
    const MESSAGE = 'invalid packet';
}
