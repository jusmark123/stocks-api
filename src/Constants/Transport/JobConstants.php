<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Constants\Transport;

final class JobConstants
{
    const JOB_CACHE_TAG = 'job';
    const JOB_NOT_FOUND = 'Job not found';
    const REQUEST_HEADER_NAME = 'JOB_REQUEST';
    const JOB_ID_HEADER_NAME = 'JOB_ID';
    const JOB_INFO_HEADER_NAME = 'JOB_INFO';

    const JOB_REQUEST_ROUTING_KEY = 'stocks-api.job.request';
    const JOB_INFO_ROUTING_KEY = 'stocks-api.job.info';
    const JOB_REQUEST_TOPIC = '';

    // Job Types
    const REQUEST_SYNC_ORDER_REQUEST = 'sync-order.request';

    // Job StatusTypes
    const JOB_COMPLETE = 'COMPLETE';
    const JOB_CREATED = 'CREATED';
    const JOB_FAILED = 'FAILED';
    const JOB_INCOMPLETE = 'INCOMPLETE';
    const JOB_INITIATED = 'INITIATED';
    const JOB_IN_PROGRESS = 'IN_PROGRESS';
    const JOB_PENDING = 'PENDING';
    const JOB_QUEUED = 'QUEUED';
    const JOB_PROCESSED = 'PROCESSED';
    const JOB_RECEIVED = 'RECEIVED';

    // Job Descriptions
    const ACCOUNT_SYNC_ORDERS_REQUEST = 'Sync orders request initiated for account: %s';
}
