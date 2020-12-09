<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Constants\Transport;

final class JobConstants
{
    const REQUEST_HEADER_NAME = 'ACCOUNT_SYNC_ORDERS_REQUEST';
    const JOB_ID_HEADER_NAME = 'JOB_ID';

    // Job Types
    const REQUEST_SYNC_ORDER_REQUEST = 'sync-order.request';

    // Job StatusTypes
    const JOB_COMPLETE = 'COMPLETE';
    const JOB_FAILED = 'FAILED';
    const JOB_INCOMPLETE = 'INCOMPLETE';
    const JOB_INITIATED = 'INITIATED';
    const JOB_PENDING = 'PENDING';
    const JOB_IN_PROGRESS = 'IN_PROGRESS';
    const JOB_PROCESSED = 'PROCESSED';
    const JOB_RECEIVED = 'RECEIVED';

    // Job Descriptions
    const ACCOUNT_SYNC_ORDERS_REQUEST = 'Sync orders request initiated for account: %s';
}
