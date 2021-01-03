<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

/**
 * Class JobItemStamp.
 */
class JobItemStamp implements StampInterface
{
    /**
     * @var string
     */
    private $jobItemId;

    /**
     * JobItemStamp constructor.
     *
     * @param string $jobItemId
     */
    public function __construct(string $jobItemId)
    {
        $this->jobItemId = $jobItemId;
    }

    /**
     * @return mixed
     */
    public function getJobItemId(): string
    {
        return $this->jobItemId;
    }
}
