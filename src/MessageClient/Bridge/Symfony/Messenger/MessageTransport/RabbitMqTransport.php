<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Bridge\Symfony\Messenger\MessageTransport;

use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Exception\InvalidPacket;
use App\MessageClient\Protocol\Packet;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\TransportInterface;

/**
 * Class RabbitMqTransport.
 */
class RabbitMqTransport implements TransportInterface
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
     * RabbitMqTransport constructor.
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
     * @param Envelope $envelope
     *
     * @return Envelope
     */
    public function send(Envelope $envelope): Envelope
    {
        try {
            $packet = $this->getPacket($envelope);

            $this->publisher->publish($packet);
        } catch (InvalidPacket $invalidPacket) {
            $this->logger->error('Invalid packet supplied', [
                'packetType' => \get_class($envelope->getMessage()),
            ]);
        } catch (\Exception $e) {
            if (isset($packet)) {
                $this->logger->error('Failed to publish message', [
                    'message' => $packet->getMessage(),
                    'exception' => $e->getMessage(),
                ]);
            } else {
                $this->logger->error('failed to publish message', [
                   'message' => 'Packet was invalid',
                   'exception' => $e->getMessage(),
                ]);
            }
        }

        return $envelope;
    }

    /**
     * @throws \Exception
     *
     * @return iterable
     */
    public function get(): iterable
    {
        // TODO: Implement get() method.
        throw new \Exception('Do not use this method');
    }

    /**
     * @param Envelope $envelope
     *
     * @throws \Exception
     */
    public function ack(Envelope $envelope): void
    {
        // TODO: Implement get() method.
        throw new \Exception('Do not use this method');
    }

    /**
     * @return ClientPublisher
     */
    public function getPublisher(): ClientPublisher
    {
        return $this->publisher;
    }

    /**
     * @param ClientPublisher $publisher
     *
     * @return RabbitMqTransport
     */
    public function setPublisher(ClientPublisher $publisher): RabbitMqTransport
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return RabbitMqTransport
     */
    public function setLogger(LoggerInterface $logger): RabbitMqTransport
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @param Envelope $envelope
     *
     * @throws \Exception
     */
    public function reject(Envelope $envelope): void
    {
        // TODO: Implement reject() method.
        throw new \Exception('Do not use this method');
    }

    /**
     * @param Envelope $envelope
     *
     * @throws InvalidPacket
     *
     * @return Packet
     */
    public function getPacket(Envelope $envelope): Packet
    {
        $packet = $envelope->getMessage();

        if (!$packet instanceof Packet) {
            throw new InvalidPacket();
        }

        return $packet;
    }
}
