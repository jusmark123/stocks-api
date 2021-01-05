<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

/**
 * Class LastJobItemStamp.
 */
final class LastJobItemStamp implements StampInterface
{
    /**
     * @var string
     */
    private $jobId;

    /**
     * LastJobItemStamp constructor.
     *
     * @param string $jobId
     */
    public function __construct(string $jobId)
    {
        $this->jobId = $jobId;
    }

    /**
     * @return string
     */
    public function getJobId(): string
    {
        return $this->jobId;
    }
}
