<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient;

use App\MessageClient\Protocol\Credentials;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

/**
 * Class AbstractRabbitAmqpClientFactory.
 */
abstract class AbstractRabbitAmqpClientFactory implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var array
     */
    protected $options;

    /**
     * AbstractRabbitAmqpClientFactory constructor.
     *
     * @param array           $options
     * @param LoggerInterface $logger
     */
    public function __construct(array $options, LoggerInterface $logger)
    {
        $this->setLogger($logger);
        $this->options = $options;
    }

    /**
     * @param Credentials|null $credentials
     *
     * @return array
     */
    protected function mungOptions(?Credentials $credentials = null)
    {
        $options = $this->options;

        if ($credentials) {
            $options = array_merge($options, $credentials->getCredentials());
        }

        return $options;
    }
}
