<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Client\Stream\Protocol;

use App\MessageClient\Protocol\Credentials;

class HandlerFactory
{
    public function createHandler(Credentials $credentials)
    {
        return $credentials;
    }
}
