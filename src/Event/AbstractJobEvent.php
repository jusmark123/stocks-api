<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event;

use App\Entity\Job;

/**
 * Class AbstractJobEvent.
 */
class AbstractJobEvent extends AbstractEvent
{
    /**
     * @var Job
     */
    protected $job;

    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    /**
     * @return mixed
     */
    public function getJob()
    {
        return $this->job;
    }
}
