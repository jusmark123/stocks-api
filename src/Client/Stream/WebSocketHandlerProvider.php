<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Client\Stream;

use Psr\Log\LoggerAwareTrait;

class WebSocketHandlerProvider
{
    use LoggerAwareTrait;

    const EXCHANGE_NAME = 'amq.fanout';
}
