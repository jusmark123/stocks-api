<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\Entity\Job;
use App\Event\AbstractFailedEvent;

/**
 * Class OrderInfoReceiveFailedEvent.
 */
class OrderInfoReceiveFailedEvent extends AbstractFailedEvent
{
    const EVENT_NAME = 'order-id.failed';

    /** @var Job */
    protected $job;

    /**
     * @var array
     */
    protected $message;

    /**
     * OrderInfoReceiveFailedEvent constructor.
     *
     * @param array      $message
     * @param \Exception $exception
     * @param Job|null   $job
     */
    public function __construct(array $message, \Exception $exception, ?Job $job = null)
    {
        $this->job = $job;
        $this->message = $message;
        parent::__construct($exception);
    }

    /**
     * @return array
     */
    public function getMessage(): array
    {
        return $this->message;
    }

    /**
     * @return Job|null
     */
    public function getJob(): ?Job
    {
        return $this->job;
    }
}
