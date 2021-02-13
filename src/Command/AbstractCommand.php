<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Command;

use React\ChildProcess\Process;
use Symfony\Component\Console\Command\Command;

abstract class AbstractCommand extends Command
{
    protected function getProcess(string $command): Process
    {
        $env = getenv();
        $env['APP_ENV'] = empty($env['APP_ENV_QUEUE_CONSOLE_COMMAND'])
            ? 'prod'
            : $env['APP_ENV_QUEUE_CONSOLE_COMMAND'];

        return new Process(
            $command,
            null,
            $env
        );
    }
}
