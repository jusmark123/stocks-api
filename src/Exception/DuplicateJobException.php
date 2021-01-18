<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Exception;

use App\Entity\Job;
use Throwable;

/**
 * Class DuplicateJobException.
 */
class DuplicateJobException extends \Exception
{
    const ERROR_MESSAGE = 'Job %s is a duplicate of job %s';

    /**
     * DuplicateJobException constructor.
     *
     * @param Job            $job
     * @param Job            $duped
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(Job $job, Job $duped, $code = 0, Throwable $previous = null)
    {
        $message = sprintf(self::ERROR_MESSAGE, $job->getGuid()->toString(), $duped->getGuid()->toString());
        parent::__construct($message, $code, $previous);
    }
}
