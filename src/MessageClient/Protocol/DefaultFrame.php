<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Protocol;

class DefaultFrame implements Frame
{
    /**
     * @var FrameType
     */
    protected $type;

    /**
     * @var Packet
     */
    protected $packet;

    /**
     * DefaultFrame Constructor.
     *
     * @param FrameType $type
     * @param Packet    $packet
     */
    public function __construct(FrameType $type, Packet $packet)
    {
        $this->type = $type;
        $this->packet = $packet;
    }

    /**
     * @return FrameType
     */
    public function getType(): FrameType
    {
        return $this->type;
    }

    /**
     * @return Packet
     */
    public function getPacket(): Packet
    {
        return $this->packet;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $packet = $this->getPacket();

        $retval = [
                  'type' => (string) $this->getType(),
                    'topic' => $packet->getTopic(),
                    'headers' => $packet->getHeaders(),
                    'message' => $packet->getMessage(),
                ];

        return $retval;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param int $options
     *
     * @return string
     */
    public function toString($options = JSON_PARTIAL_OUTPUT_ON_ERROR)
    {
        return json_encode($this->toArray(), $options);
    }
}
