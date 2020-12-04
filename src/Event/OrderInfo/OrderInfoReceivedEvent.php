<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\Entity\Job;
use App\Event\AbstractEvent;

class OrderInfoReceivedEvent extends AbstractEvent
{
    const EVENT_NAME = 'order-info.reveived';

    /** @var Job */
    private $job;

    /** @var array */
    protected $orderInfoMessage;

    /**
     * OrderInfoReceivedEvent constructor.
     *
     * @param array $orderInfoMessage
     * @param Job   $job
     */
    public function __construct(array $orderInfoMessage, Job $job)
    {
        $this->job = $job;
        $this->orderInfoMessage = $orderInfoMessage;
    }

    /**
     * @return array
     */
    public function getOrderInfoMessage(): array
    {
        return $this->orderInfoMessage;
    }

    /**
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }
}
