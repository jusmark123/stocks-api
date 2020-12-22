<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event;

use App\Entity\Job;
use App\Entity\JobItem;
use App\Event\Job\JobEventTrait;

/**
 * Class AbstractJobFailedEvent.
 */
class AbstractJobFailedEvent extends AbstractFailedEvent
{
    use JobEventTrait;

    /**
     * @var array|null
     */
    protected $jobMessage;

    /**
     * AbstractJobFailedEvent constructor.
     *
     * @param \Exception   $exception
     * @param Job|null     $job
     * @param JobItem|null $jobItem
     * @param array|null   $jobMessage
     */
    public function __construct(
        \Exception $exception,
        ?Job $job = null,
        ?JobItem $jobItem = null,
        ?array $jobMessage = null
    ) {
        $this->jobItem = $jobItem;
        $this->job = $job;
        $this->jobMessage = $jobMessage;
        parent::__construct($exception);
    }

    /**
     * @return array
     */
    public function getJobMessage(): array
    {
        return $this->jobMessage;
    }

    /**
     * @param Job $job
     */
    public function setJob(Job $job)
    {
        $this->job = $job;
    }
}
