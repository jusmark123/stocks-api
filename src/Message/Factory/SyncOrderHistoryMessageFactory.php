<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Factory;

use App\Message\SyncOrderHistoryMessage;

/**
 * Class SyncOrderHistoryMessageFactory.
 */
class SyncOrderHistoryMessageFactory
{
    public static function create(string $jobId, string $jobItemId, array $orderHistory)
    {
        return new SyncOrderHistoryMessage($jobId, $jobItemId, $orderHistory);
    }
}
