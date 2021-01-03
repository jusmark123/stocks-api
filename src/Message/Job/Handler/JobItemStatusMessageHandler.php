<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Job\Handler;

use App\Message\Job\JobItemStatusMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class JobItemStatusMessageHandler.
 */
class JobItemStatusMessageHandler implements MessageHandlerInterface
{
    public function __invoke(JobItemStatusMessage $message)
    {
        return $message;
    }
}
