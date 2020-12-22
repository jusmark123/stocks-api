<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Entity\Job;
use App\Event\AbstractFailedEvent;

/**
 * Class JobIncompleteEvent.
 */
class JobIncompleteEvent extends AbstractFailedEvent
{
    const EVENT_NAME = 'job.incomplete';

    /** @var Job */
    private $job;

    /**
     * JobIncompleteEvent constructor.
     *
     * @param Job        $job
     * @param \Exception $exception
     */
    public function __construct(
        Job $job,
        \Exception $exception
    ) {
        $this->job = $job;
        parent::__construct($exception);
    }

    /**
     * @return Job
     */
    public function getJob()
    {
        return $this->job;
    }
}
