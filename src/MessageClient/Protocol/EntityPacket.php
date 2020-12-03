<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Protocol;

use App\MessageClient\Bridge\Symfony\Messenger\Message\EntityMessageAwareInterface;
use App\MessageClient\Exception\InvalidMessage;
use Symfony\Component\Serializer\SerializerInterface;

class EntityPacket extends DefaultPacket
{
    public const SERIALIZATION_CONTEXT = 'serialization_context';
    public const DEFAULT_CONTEXT_GROUP = 'entity_message';

    protected $serializer;

    public function __construct(
        SerializerInterface $serialier,
        string $topic = '',
        $message = null,
        array $headers = []
    ) {
        $this->serialier = $serialier;

        if (!$message instanceof EntityMessageAwareInterface) {
            throw new InvalidMessage();
        }

        parent::__construct($topic, $message, $headers);
    }
}
