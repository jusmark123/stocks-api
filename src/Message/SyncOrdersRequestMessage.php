<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message;

use App\DTO\SyncOrdersRequest;
use App\Message\Job\AbstractJobRequestMessage;
use App\Message\Job\JobRequestMessageInterface;

/**
 * Class SyncOrdersRequestMessage.
 */
class SyncOrdersRequestMessage extends AbstractJobRequestMessage implements JobRequestMessageInterface
{
    const JOB_NAME = 'sync-order-history';
    const JOB_DESCRIPTION = 'Sync account order history';

    /**
     * SyncOrdersRequestMessage constructor.
     *
     * @param SyncOrdersRequest $request
     */
    public function __construct(SyncOrdersRequest $request)
    {
        parent::__construct($request);
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getJobName(): string
    {
        return self::JOB_NAME;
    }

    /**
     * @return string
     */
    public function getJobDescription(): string
    {
        return self::JOB_DESCRIPTION;
    }

    /**
     * @return SyncOrdersRequest
     */
    public function getRequest(): SyncOrdersRequest
    {
        return $this->request;
    }
}
