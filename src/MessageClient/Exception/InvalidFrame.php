<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Exception;

class InvalidFrame extends Exception
{
    const MESSAGE = 'invalid frame';
}
