<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Entity\Job;
use App\Entity\JobItem;
use App\Event\AbstractFailedEvent;
use App\Message\Job\JobRequestMessageInterface;

/**
 * Class AbstractJobFailedEvent.
 */
class AbstractJobFailedEvent extends AbstractFailedEvent
{
    use JobEventTrait;

    /**
     * @var JobRequestMessageInterface
     */
    protected $message;

    /**
     * AbstractJobFailedEvent constructor.
     *
     * @param \Exception                      $exception
     * @param Job|null                        $job
     * @param JobItem|null                    $jobItem
     * @param JobRequestMessageInterface|null $message
     */
    public function __construct(
        \Exception $exception,
        ?Job $job = null,
        ?JobItem $jobItem = null,
        ?JobRequestMessageInterface $message = null
    ) {
        $this->jobItem = $jobItem;
        $this->job = $job;
        $this->message = $message;
        parent::__construct($exception);
    }

    /**
     * @return JobRequestMessageInterface
     */
    public function getJobMessage(): JobRequestMessageInterface
    {
        return $this->message;
    }

    /**
     * @param Job $job
     */
    public function setJob(Job $job)
    {
        $this->job = $job;
    }
}
