<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Protocol;

class ConsummableFrame implements Frame
{
    /**
     * @var bool
     */
    protected $consume;

    /**
     * @var Frame
     */
    protected $decorated;

    public function __construct(Frame $decorated)
    {
        $this->decorated = $decorated;
        $this->consumed = false;
    }

    /**
     * @return bool
     */
    public function isConsumed(): bool
    {
        return $this->consume;
    }

    /**
     * Set the value of Consume.
     *
     * @param bool $consume
     */
    public function setConsume($consume): void
    {
        $this->consume = $consume;
    }

    /**
     * @return FrameType
     */
    public function getType(): FrameType
    {
        return $this->getType();
    }

    public function getPacket(): Packet
    {
        return $this->getPacket();
    }

    /**
     * @return Frame
     */
    public function getDecorated(): Frame
    {
        return $this->decorated;
    }

    /**
     * Set the value of Decorated.
     *
     * @param Frame $decorated
     */
    public function setDecorated(Frame $decorated): void
    {
        $this->decorated = $decorated;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->decorated->toArray();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->decorated->__toString();
    }

    /**
     * @return [type]
     */
    public function toString()
    {
        return $this->decorated->toString();
    }
}
