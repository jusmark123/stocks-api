<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message;

use App\Message\Job\JobMessageInterface;
use App\Message\Job\Traits\JobIdAwareTrait;
use App\Message\Job\Traits\JobItemAwareTrait;

/**
 * Class SyncTickerMessage.
 */
class SyncTickerMessage implements JobMessageInterface
{
    use JobIdAwareTrait;
    use JobItemAwareTrait;

    /**
     * @var array
     */
    private $tickerData;

    /**
     * SyncTickerMessage constructor.
     *
     * @param string $jobId
     * @param string $jobItemId
     * @param array  $tickerData
     */
    public function __construct(string $jobId, string $jobItemId, array $tickerData)
    {
        $this->jobId = $jobId;
        $this->jobItemId = $jobItemId;
        $this->tickerData = $tickerData;
    }

    /**
     * @return array
     */
    public function getMessage(): array
    {
        return $this->tickerData;
    }
}
