<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO;

use App\DTO\Traits\JobRequestTrait;
use App\Entity\Job;

/**
 * Class CancelJobRequest.
 */
class CancelJobRequest
{
    use JobRequestTrait;

    /**(
     * @var Job
     */
    private $job;

    /**
     * @return mixed
     */
    public function getJob()
    {
        return $this->job;
    }
}
