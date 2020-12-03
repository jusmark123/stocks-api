<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\ClientPublisher;

use App\MessageClient\Exception\ConnectionException;
use App\MessageClient\Exception\PublishException;
use App\MessageClient\Protocol\CredentialHandler;
use App\MessageClient\Protocol\Packet;
use App\MessageClient\SyncClientFactory;
use Bunny\Channel;
use Bunny\Client;
use Psr\Log\LoggerInterface;

class BunnyClientPublisher implements ClientPublisher
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var CredentialHandler
     */
    protected $credentialHandler;

    /**
     * @var SyncClientFactory
     */
    protected $clientFactory;

    /**
     * @var Channel
     */
    protected $channel;

    /**
     * @var string
     */
    protected $exchangeName;

    /**
     * @var string
     */
    protected $publisherName;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(
        ?CredentialHandler $credentialHandler,
        SyncClientFactory $clientFactory,
        string $exchangeName,
        string $publisherName,
        LoggerInterface $logger
    ) {
        $this->credentialHandler = $credentialHandler;
        $this->clientFactory = $clientFactory;
        $this->exchangeName = $exchangeName;
        $this->publisherName = $publisherName;
        $this->logger = $logger;
    }

    protected function _publish(Packet $packet, $count = 0)
    {
        try {
            $this->connect();

            return $this->channel->publish(
                json_encode($packet),
                [],
                $this->exchangeName,
                $packet->getTopic()
            );
        } catch (\Throwable $e) {
            $this->logger->error(
                'publish failed',
                [
                    'exception' => $e,
                    'retryCount' => $count,
                ]
            );
            if (++$count < 3) {
                $this->disconnect();

                return $this->_publish($packet, $count);
            }
            throw new PublishException('retried publishing 3 times');
        }
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client): BunnyClientPublisher
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @param Packet $packet
     *
     * @throws PublishException
     *
     * @return bool|int|\React\Promise\PromiseInterface
     */
    public function publish(Packet $packet)
    {
        if (!$packet->hasHeader('publisher')) {
            $packet->addHeader('publisher', $this->publisherName);
        }

        try {
            return $this->_publish($packet);
        } catch (PublishException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new PublishException($e);
        }
    }

    public function connect()
    {
        try {
            if (!$this->client) {
                $credentials = null;

                // Todo: Add Rabbit Authentication in Authentication Bundle

//                $grant = $this->rabbitAuthorizeService->authorize();
//
//                if(!$grant) {
//                    throw new \Exception('no access granted');
//                }
//
//                $credentials = $this->credentialHandler->getCredentials($grant->getAccessToken());

                $this->client = $this->clientFactory->createClient();
            }

            if (!$this->client->isConnected()) {
                $this->client->connect();
                $this->channel = null;
            }

            if (!$this->channel) {
                $this->channel = $this->client->channel();
            }
        } catch (\Throwable $e) {
            throw new ConnectionException($e);
        }
    }

    public function disconnect()
    {
        try {
            if ($this->channel) {
                $this->channel->close();
            }
        } catch (\Throwable $e) {
            $this->logger->error('channel close error',
                [
                    'exception' => $e,
                ]
            );
        }

        $this->channel = null;

        try {
            if ($this->client) {
                $this->client->disconnect();
            }
        } catch (\Throwable $e) {
            $this->logger->error('client disconnect error',
                [
                    'exception' => $e,
                ]
            );
        }

        $this->client = null;

        $this->logger->info('disconnection successful');
    }
}
