<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Job;

use App\Entity\Job;
use App\Entity\JobItem;
use App\Message\Job\JobRequestMessageInterface;

/**
 * Traits JobFailedEventTrait.
 */
trait JobFailedEventTrait
{
    /**
     * @var Job|null
     */
    protected ?Job $job;

    /**
     * @var JobItem|null
     */
    protected ?JobItem $jobItem;

    /**
     * @var JobRequestMessageInterface|null
     */
    protected ?JobRequestMessageInterface $message;

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

        parent::__construct($exception, $jobItem);
    }

    /**
     * @return Job|null
     */
    public function getJob(): ?Job
    {
        return $this->job;
    }

    /**
     * @return JobItem|null
     */
    public function getJobItem(): ?JobItem
    {
        return $this->jobItem;
    }

    /**
     * @return JobRequestMessageInterface|null
     */
    public function getMessage(): ?JobRequestMessageInterface
    {
        return $this->message;
    }
}
