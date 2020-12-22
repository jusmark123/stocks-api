<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Command;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Class StreamsConnectCommand.
 */
class StreamsConnectCommand extends Command implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const NAME = 'stocks-api:streams:connect';

    private $streamsHandler;

    public function __construct(
        LoggerInterface $logger
    ) {
        parent::__construct(self::NAME);
        $this->setLogger($logger);
    }
}
