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
 * Class SyncOrderHistoryMessage.
 */
class SyncOrderHistoryMessage implements JobMessageInterface
{
    use JobIdAwareTrait;
    use JobItemAwareTrait;

    /**
     * @var array
     */
    private array $orderHistory;

    /**
     * SyncOrderHistoryMessage constructor.
     *
     * @param string $jobId
     * @param string $jobItemId
     * @param array  $orderHistory
     */
    public function __construct(string $jobId, string $jobItemId, array $orderHistory)
    {
        $this->jobId = $jobId;
        $this->jobItemId = $jobItemId;
        $this->orderHistory = $orderHistory;
    }

    /**
     * @return array
     */
    public function getMessage(): array
    {
        return $this->orderHistory;
    }
}
