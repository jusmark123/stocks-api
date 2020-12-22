<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\ClientListener;

use App\MessageClient\ClientListener\ClientListener;
use Psr\Log\LoggerInterface;
use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;

/**
 * Class AbstractCommandListener.
 */
abstract class AbstractCommandListener implements ClientListener
{
    /**
     * @var LoopInterface
     */
    protected $loop;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * AbstractCommandListener constructor.
     *
     * @param LoopInterface   $loop
     * @param LoggerInterface $logger
     */
    public function __construct(LoopInterface $loop, LoggerInterface $logger)
    {
        $this->loop = $loop;
        $this->logger = $logger;
    }

    /**
     * @param string $command
     *
     * @return Process
     */
    protected function getProcess(string $command): Process
    {
        $env = getenv();
        $env['APP_ENV'] = empty($env['APP_ENV_QUEUE_CONSOLE_COMMAND']) ? 'prod' : $env['APP_ENV_QUEUE_CONSOLE_COMMAND'];

        return new Process($command, null, $env);
    }
}
