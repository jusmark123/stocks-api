<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Factory;

use App\Message\SyncTickerMessage;

/**
 * Class SyncTickerMessageFactory.
 */
class SyncTickerMessageFactory
{
    /**
     * @param string $jobId
     * @param string $jobItemId
     * @param array  $tickerData
     *
     * @return SyncTickerMessage
     */
    public static function create(string $jobId, string $jobItemId, array $tickerData): SyncTickerMessage
    {
        return new SyncTickerMessage($jobId, $jobItemId, $tickerData);
    }
}
