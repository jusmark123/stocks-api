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

    const TICKERS_PERSISTENT_ROUTING_KEY = 'stocks-api.ticker';
    const JOB_REQUEST_PERSISTENT_ROUTING_KEY = 'stocks-api.job.request';
    const ORDER_INFO_PERSISTENT_ROUTING_KEY = 'stocks-api.order.info';

    const TOPIC_EXCHANGE = 'amq.topic';
    const FANOUT_EXCHANGE = 'amq.fanout';

    const REQUEST_HEADERS = [
        self::SYSTEM_PUBLISHER_HEADER_NAME => self::SYSTEM_PUBLISHER_NAME,
    ];
}
