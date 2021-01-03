<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Exception;

class JobCompletedException extends \Exception
{
    public function __construct($job)
    {
        parent::__construct(sprintf('Job previously completed on %s. Please start new job.', date_format($job->getModifiedAt(), 'y-m-d H:i:s')));
    }
}
