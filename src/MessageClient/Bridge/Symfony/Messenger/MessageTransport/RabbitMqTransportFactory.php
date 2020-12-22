<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Bridge\Symfony\Messenger\MessageTransport;

use App\MessageClient\ClientPublisher\ClientPublisher;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class RabbitMqTransportFactory.
 */
class RabbitMqTransportFactory
{
    /**
     * @var ClientPublisher
     */
    private $publisher;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * RabbitMqTransportFactory constructor.
     *
     * @param ClientPublisher $publisher
     * @param LoggerInterface $logger
     */
    public function __construct(ClientPublisher $publisher, LoggerInterface $logger)
    {
        $this->publisher = $publisher;
        $this->logger = $logger;
    }

    /**
     * @param string              $dns
     * @param array               $options
     * @param SerializerInterface $serializer
     *
     * @return TransportInterface
     */
    public function createTransport(string $dns, array $options, SerializerInterface $serializer): TransportInterface
    {
        return RabbitMqTransport($this->publisher, $this->logger);
    }

    /**
     * @param string $dsn
     * @param array  $options
     *
     * @return bool
     */
    public function supports(string $dsn, array $options): bool
    {
        return 0 === strpos($dsn, 'stocks-api-rabbitmq');
    }
}
