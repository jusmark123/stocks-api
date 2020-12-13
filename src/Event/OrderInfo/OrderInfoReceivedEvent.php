<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\Entity\Job;
use App\Entity\JobDataItem;
use App\Event\AbstractJobEvent;

class OrderInfoReceivedEvent extends AbstractJobEvent
{
    const EVENT_NAME = 'order-info.receive';

    /** @var JobDataItem */
    protected $orderInfoMessage;

    /**
     * OrderInfoReceivedEvent constructor.
     *
     * @param array $orderInfoMessage
     * @param Job   $job
     */
    public function __construct(array $orderInfoMessage, Job $job)
    {
        $this->orderInfoMessage = $orderInfoMessage;
        parent::__construct($job);
    }

    /**
     * @return array
     */
    public function getOrderInfoMessage(): array
    {
        return $this->orderInfoMessage;
    }
}
