<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Job\Handler;

use App\Message\Job\JobStatusMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class JobStatusMessageHandler.
 */
class JobStatusMessageHandler implements MessageHandlerInterface
{
    public function __invoke(JobStatusMessage $message)
    {
        return $message;
    }
}
