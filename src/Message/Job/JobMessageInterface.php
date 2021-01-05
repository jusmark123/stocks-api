<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Job;

/**
 * Interface JobMessageInterface.
 */
interface JobMessageInterface
{
    /**
     * @return string
     */
    public function getJobItemId(): string;

    public function getMessage(): array;
}
