<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Exception;

class JobCancelledException extends \Exception
{
    /**
     * JobCancelledException constructor.
     */
    public function __construct()
    {
        parent::__construct('Job Cancelled');
    }
}
