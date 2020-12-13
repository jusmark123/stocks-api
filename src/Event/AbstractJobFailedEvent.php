<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event;

use App\Entity\Job;

/**
 * Class AbstractJobFailedEvent.
 */
class AbstractJobFailedEvent extends AbstractFailedEvent
{
    /**
     * @var
     */
    protected $job;

    /**
     * AbstractJobFailedEvent constructor.
     *
     * @param Job        $job
     * @param \Exception $exception
     */
    public function __construct(Job $job, \Exception $exception)
    {
        $this->job = $job;
        parent::__construct($exception);
    }

    /**
     * @return mixed
     */
    public function getJob()
    {
        return $this->job;
    }
}
