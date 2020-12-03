<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Event\AbstractFailedEvent;

/**
 * Class JobReceivedFailedEvent.
 */
class JobReceiveFailedEvent extends AbstractFailedEvent
{
    const EVENT_NAME = 'job.failed';

    /**
     * @var array
     */
    protected $message;

    /**
     * JobReceiveFailedEvent constructor.
     *
     * @param array      $message
     * @param \Exception $exception
     */
    public function __construct(array $message, \Exception $exception)
    {
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
}
