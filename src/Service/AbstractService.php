<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

/**
 * Class AbstractService.
 */
abstract class AbstractService implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * AbstractService constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
