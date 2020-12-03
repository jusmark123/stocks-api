<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Entity\Job;
use App\Event\AbstractEvent;

/**
 * Class JobReceivedEvent.
 */
class JobReceivedEvent extends AbstractEvent
{
    const EVENT_NAME = 'job.recieved';

    /**
     * @var Job
     */
    protected $job;

    /**
     * @var array
     */
    protected $message;

    /**
     * JobReceivedEvent constructor.
     *
     * @param array    $message
     * @param Job|null $job
     */
    public function __construct(array $message, ?Job $job)
    {
        $this->job = $job;
        $this->message = $message;
    }

    /**
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }

    /**
     * @return array
     */
    public function getMessage(): array
    {
        return $this->message;
    }
}
