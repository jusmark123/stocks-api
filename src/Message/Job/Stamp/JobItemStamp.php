<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Job\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

/**
 * Class JobItemStamp.
 */
class JobItemStamp implements StampInterface
{
    /**
     * @var string
     */
    private $jobId;

    /**
     * @var string
     */
    private $jobItemId;

    /**
     * JobItemStamp constructor.
     *
     * @param string $jobItemId
     */
    public function __construct(string $jobId, string $jobItemId)
    {
        $this->jobId;
        $this->jobItemId = $jobItemId;
    }

    /**
     * @return string
     */
    public function getJobId(): string
    {
        return $this->jobId;
    }

    /**
     * @return mixed
     */
    public function getJobItemId(): string
    {
        return $this->jobItemId;
    }
}
