<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Service;

use ApiPlatform\Core\Api\IriConverterInterface;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Entity\MessagesEntityInterface;
use App\MessageClient\Protocol\MessageFactory;
use App\MessageClient\Protocol\Packet;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class MessagesService.
 */
class MessagesService
{
    const CONSOLE_TERMINATE_MESSAGE = 'ConsoleTerminatedEvent with exit code 0';
    const CREATED_PACKET_WITH_DATA = 'Creating package with top "%s" and message: "%s"';
    const UNKNOWN_EXCEPTION = 'Unknown exception encountered while creating message: %s';
    const PUBLISH_EXCEPTION = 'failed to publish message: %s';

    /**
     * @var IriConverterInterface
     */
    private $iriConverter;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @var ClientPublisher
     */
    private $clientPublisher;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $messages = [];

    /**
     * MessagesService constructor.
     *
     * @param IriConverterInterface $iriConverter
     * @param SerializerInterface   $serializer
     * @param MessageFactory        $messageFactory
     * @param ClientPublisher       $clientPublisher
     * @param LoggerInterface       $logger
     */
    public function __construct(
        IriConverterInterface $iriConverter,
        SerializerInterface $serializer,
        MessageFactory $messageFactory,
        ClientPublisher $clientPublisher,
        LoggerInterface $logger
    ) {
        $this->iriConverter = $iriConverter;
        $this->serializer = $serializer;
        $this->messageFactory = $messageFactory;
        $this->clientPublisher = $clientPublisher;
        $this->logger = $logger;
    }

    /**
     * @param string $iri
     * @param string $action
     *
     * @return string
     */
    public function convertIriAndActionToTopic(string $iri, string $action)
    {
        return str_replace('/', '.', trim($iri, '/')).'.'.$action;
    }

    /**
     * @param string                  $action
     * @param MessagesEntityInterface $entity
     */
    public function createMessaage(string $action, MessagesEntityInterface $entity)
    {
        try {
            $topic = $this->convertIriAndActionToTopic($this->iriConverter->getIriFromItem($entity), $action);
            $message = $this->serializer->serialize($entity, 'json');

            $this->logger->debug(sprintf(self::CREATED_PACKET_WITH_DATA.$topic, $message));
            $this->addMessage($this->messageFactory->createPacket($topic, $message));
        } catch (\Exception $e) {
            $this->logger->error(sprintf(self::UNKNOWN_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param Packet $message
     */
    public function addMessage(Packet $message): void
    {
        $this->messages[] = $message;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    public function clearMessages(): void
    {
        $this->messages = [];
    }

    public function sendMessages(): void
    {
        try {
            foreach ($this->getMessages() as $message) {
                $this->clientPublisher->publish($message);
            }
        } catch (\Exception $exception) {
            $this->clearMessages();
        }
    }
}
