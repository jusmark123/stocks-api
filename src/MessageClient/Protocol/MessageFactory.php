<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Protocol;

use App\MessageClient\Entity\MessagesEntityInterface;
use App\MessageClient\Exception\InvalidFrame;
use App\MessageClient\Exception\InvalidMessage;
use App\MessageClient\Exception\InvalidMessageType;
use App\MessageClient\Exception\InvalidPacket;
use Bunny\Message as AmqpMessage;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class MessageFactory.
 */
class MessageFactory implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var FrameTypeCollection
     */
    protected $frameTypes;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $version;

    /**
     * MessageFactory constructor.
     *
     * @param SerializerInterface $serializer
     * @param $frameTypes
     * @param string          $namespace
     * @param string          $name
     * @param string          $version
     * @param LoggerInterface $logger
     *
     * @throws \App\MessageClient\Exception\InvalidFrameType
     */
    public function __construct(
         SerializerInterface $serializer,
         $frameTypes,
         string $namespace,
         string $name,
         string $version,
         LoggerInterface $logger
     ) {
        $this->serializer = $serializer;
        $this->setFrameTypes(FrameTypeCollection::create($frameTypes));
        $this->namespace = $namespace;
        $this->name = $name;
        $this->version = $version;
        $this->logger = $logger;
    }

    /**
     * @return FrameTypeCollection
     */
    public function getFrameTypes(): FrameTypeCollection
    {
        return $this->frameTypes;
    }

    /**
     * @param FrameTypeCollection $frameTypes
     *
     * @return $this
     */
    public function setFrameTypes(FrameTypeCollection $frameTypes): MessageFactory
    {
        $this->frameTypes = $frameTypes;

        return $this;
    }

    /**
     * @param string $frame
     *
     * @throws InvalidFrame
     * @throws \App\MessageClient\Exception\InvalidMessage
     *
     * @return Frame
     */
    public function createFrameFromMessage(string $frame): Frame
    {
        $frame = json_decode($frame, true);

        if (!$frame || !\is_array($frame) || !$frame['type']) {
            throw new InvalidFrame('invalid fram: no type given');
        }

        $frameType = $this->getFrameType($frame['type']);

        if (empty($frame['topic'])) {
            $frame['topic'] = '';
        }

        if (!\array_key_exists('message', $frame)) {
            $frame['message'] = null;
        }

        $headers = [];

        if (!empty($frame['headers']) && \is_array($frame['headers'])) {
            $headers = $frame['headers'];
        }

        $packet = $this->createPacket(
            $frame['topic'],
            $frame['message'],
            $headers
        );

        return new DefaultFrame($frameType, $packet);
    }

    /**
     * @param $frameType
     * @param string $topic
     * @param string $data
     * @param array  $headers
     *
     * @throws InvalidFrame
     * @throws \App\MessageClient\Exception\InvalidMessage
     *
     * @return DefaultFrame
     */
    public function createFrame(
        $frameType,
        string $topic,
        $data = '',
        array $headers = []
    ) {
        return $this->createFromPacket(
            $frameType,
            $this->createPacket(
                $topic,
                $data,
                $headers
            )
        );
    }

    /**
     * @param $frameType
     * @param Packet $packet
     *
     * @throws InvalidFrame
     *
     * @return DefaultFrame
     */
    public function createFromPacket(
        $frameType,
        Packet $packet
    ) {
        return new DefaultFrame(
          $this->getFrameType($frameType),
          $packet
        );
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function hasFrameType(string $type): bool
    {
        return \array_key_exists(
            $type,
            $this->getFrameTypes()->getFrames()
        );
    }

    /**
     * @param string $type
     *
     * @throws InvalidFrame
     *
     * @return FrameType
     */
    public function getFrameType(string $type): FrameType
    {
        if ($type instanceof FrameType) {
            $type = (string) $type;
        }

        if (!$this->hasFrameType($type)) {
            throw new InvalidFrame('unknown frame type');
        }

        return $this->getFrameTypes()->getFrames()[$type];
    }

    /**
     * @param string $topic
     * @param null   $message
     * @param array  $headers
     *
     * @throws \App\MessageClient\Exception\InvalidMessage
     *
     * @return DefaultPacket|EntityPacket
     */
    public function createPacket(
        string $topic = '',
        $message = null,
        array $headers = []
    ) {
        return $message instanceof MessagesEntityInterface
            ? new EntityPacket($this->serializer, $topic, $message, $headers)
            : new DefaultPacket($topic, $message, $headers);
    }

    /**
     * @param string $topic
     *
     * @return string
     */
    public function getNamespacedTopic(string $topic)
    {
        return sprintf('%s.%s.%s.%s', $this->namespace, $this->name, $this->version, $topic);
    }

    /**
     * @param string $message
     *
     * @throws InvalidPacket
     * @throws \App\MessageClient\Exception\InvalidMessage
     *
     * @return DefaultPacket|EntityPacket
     */
    public function createPacketFromString(string $message)
    {
        if (empty($message)) {
            throw new InvalidPacket();
        }

        $data = json_decode($message, true);

        if (!\is_array($data)) {
            throw new InvalidPacket();
        }

        if (empty($data['topic']) || !\is_string($data['topic'])) {
            throw new InvalidPacket();
        }

        if (!\array_key_exists('message', $data)) {
            throw new InvalidPacket();
        }

        $headers = empty($data['headers']) ? [] : $data['headers'];

        return $this->createPacket($data['topic'], $data['message'], $headers);
    }

    /**
     * @param $message
     *
     * @throws InvalidMessage
     * @throws InvalidMessageType
     * @throws InvalidPacket
     *
     * @return Packet
     */
    public function translateToNativePacket($message): Packet
    {
        if ($message instanceof AmqpMessage) {
            $message = $message->content;
        } elseif (!\is_string($message)) {
            throw new InvalidMessageType();
        }

        return $this->createPacketFromString($message);
    }
}
