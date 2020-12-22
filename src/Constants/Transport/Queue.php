<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Constants\Transport;

final class Queue
{
    const EVENT_NAME_REQUEST_HEADER = 'EVENT_NAME';

    const SYSTEM_PUBLISHER_HEADER_NAME = 'PUBLISHER';
    const SYSTEM_PUBLISHER_NAME = 'api.stocks-api';

    const JOB_PERSISTENT_ROUTING_KEY = 'stocks-api.job';
    const TICKERS_PERSISTENT_ROUTING_KEY = 'stocks-api.ticker';

    const ORDER_INFO_PERSISTENT_ROUTING_KEY = 'stocks-api.order.info';
}
