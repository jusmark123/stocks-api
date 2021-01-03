<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Job\Traits;

/**
 * Trait JobItemAwareTrait.
 */
trait JobItemAwareTrait
{
    /**
     * @var string
     */
    protected $jobItemId;

    /**
     * @return string
     */
    public function getJobItemId(): string
    {
        return $this->jobItemId;
    }
}
