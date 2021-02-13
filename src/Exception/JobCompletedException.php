<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Exception;

use App\Entity\Job;

/**
 * Class JobCompletedException.
 */
class JobCompletedException extends \Exception
{
    /**
     * @var
     */
    private $job;

    /**
     * JobCompletedException constructor.
     *
     * @param $job
     */
    public function __construct($job)
    {
        parent::__construct(sprintf('Job previously completed on %s. Please start new job.', date_format($job->getModifiedAt(), 'y-m-d H:i:s')));
    }

    /**
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }
}
