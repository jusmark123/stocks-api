<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Job;

use App\Message\Job\Traits\JobIdAwareTrait;

/**
 * Class AbstractJobRequestMessage.
 */
class AbstractJobRequestMessage
{
    use JobIdAwareTrait;

    protected const JOB_NAME = '';
    protected const JOB_DESCRIPTION = '';

    /**
     * @var mixed
     */
    protected $request;

    /**
     * AbstractJobRequestMessage constructor.
     *
     * @param mixed $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getJobName(): string
    {
        return self::JOB_NAME;
    }

    /**
     * @return string
     */
    public function getJobDescription(): string
    {
        return self::JOB_DESCRIPTION;
    }
}
