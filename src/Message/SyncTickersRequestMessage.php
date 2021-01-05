<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message;

use App\DTO\SyncTickersRequest;
use App\Message\Job\AbstractJobRequestMessage;
use App\Message\Job\JobRequestMessageInterface;
use App\Message\Job\Traits\JobIdAwareTrait;

/**
 * Class SyncTickersRequestMessage.
 */
class SyncTickersRequestMessage extends AbstractJobRequestMessage implements JobRequestMessageInterface
{
    use JobIdAwareTrait;

    const JOB_NAME = 'sync-tickers';
    const JOB_DESCRIPTION = 'Sync tickers from polygon.io';

    /**
     * SyncTickersRequestMessage constructor.
     *
     * @param SyncTickersRequest $request
     */
    public function __construct(SyncTickersRequest $request)
    {
        parent::__construct($request);
    }
}
