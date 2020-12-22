<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient;

use App\MessageClient\BunnyAsyncClient as Client;
use App\MessageClient\Exception\RabbitAmqpClientException;
use App\MessageClient\Exception\RabbitAmqpConnectionException;
use App\MessageClient\Protocol\Credentials;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use React\Promise;
use React\Promise\ExtendedPromiseInterface;

/**
 * Class BunnyAmqpClientFactory.
 */
class BunnyAmqpClientFactory extends AbstractRabbitAmqpClientFactory implements ClientFactory
{
    /**
     * @var LoopInterface
     */
    protected $loop;

    /**
     * BunnyAmqpClientFactory constructor.
     *
     * @param LoggerInterface $logger
     * @param LoopInterface   $loop
     * @param array           $options
     */
    public function __construct(LoggerInterface $logger, LoopInterface $loop, array $options)
    {
        parent::__construct($options, $logger);
        $this->loop = $loop;
    }

    /**
     * @param Credentials $credentials
     *
     * @return BunnyAsyncClient
     */
    protected function newClient(Credentials $credentials)
    {
        $options = $this->mungOptions($credentials);

        return new Client($this->loop, $options, $this->logger);
    }

    /**
     * @param Credentials $credentials
     *
     * @return ExtendedPromiseInterface
     */
    public function createClient(Credentials $credentials): ExtendedPromiseInterface
    {
        try {
            $client = $this->newClient($credentials);

            return $client->connect()->then(
                null,
                function (\Throwable $e) {
                    throw new RabbitAmqpConnectionException($e);
                }
            );
        } catch (\Throwable $e) {
            $this->logger->error('error while creating client', ['exception' => $e]);

            return Promise\reject(new RabbitAmqpClientException());
        }
    }
}
