<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Factory;

use App\Entity\Job;

class JobMessageFactory extends AbstractMessageFactory
{
    /**
     * @param array $message
     *
     * @return Job
     */
    public function createFromMessage(array $message): Job
    {
        return $this->createFromReceivedMessage($message, Job::class);
    }
}
