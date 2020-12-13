<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Entity\Job;
use App\Event\AbstractFailedEvent;

/**
 * Class JobReceivedFailedEvent.
 */
class JobReceiveFailedEvent extends AbstractFailedEvent
{
    const EVENT_NAME = 'job.receive';

    /**
     * @var array
     */
    protected $message;

    /**
     * @var Job
     */
    protected $job;

    /**
     * JobReceiveFailedEvent constructor.
     *
     * @param array      $message
     * @param \Exception $exception
     * @param Job|null   $job
     */
    public function __construct(array $message, \Exception $exception, ?Job $job = null)
    {
        $this->message = $message;
        $this->job = $job;
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
     * @return Job
     */
    public function getJob(): ?Job
    {
        return $this->job;
    }
}
