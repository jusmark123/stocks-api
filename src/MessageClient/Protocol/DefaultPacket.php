<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Protocol;

class DefaultPacket implements Packet
{
    use TopicTrait;

    /**
     * @var mixed
     */
    protected $message;

    /**
     * @var mixed
     */
    protected $originalMessage;

    /**
     * DefaultPacket constructor.
     *
     * @param string $topic
     * @param $message
     * @param array $headers
     */
    public function __construct(
     string $topic,
     $message,
     array $headers
    ) {
        $this->setMessage($message);
        $this->setTopic($topic);
        $this->setHeaders($headers);
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
          'topic' => $this->getTopic(),
          'headers' => $this->getHeaders(),
          'message' => $this->getMessage(),
        ];
    }

    /**
     * make clones.
     */
    public function __clone()
    {
        if (\is_object($this->message)) {
            $this->message = clone $this->message;
        }
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of Message.
     *
     * @param mixed $message
     *
     * @return self
     */
    public function setMessage($message): Packet
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOriginalMessage()
    {
        return $this->originalMessage;
    }

    /**
     * Set the value of Original Message.
     *
     * @param mixed $originalMessage
     *
     * @return self
     */
    public function setOriginalMessage($originalMessage): Packet
    {
        $this->originalMessage = $originalMessage;

        return $this;
    }
}
