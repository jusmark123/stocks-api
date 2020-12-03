<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Exception;

class InvalidCredentials extends Exception
{
    const MESSAGE = 'invalid credentials';
}
