<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Aws\Sns;

/**
 * Class PublishMessageResponse.
 */
class PublishMessageResponse
{
    /**
     * @var string
     */
    private $messageId;

    /**
     * @var string|null
     */
    private $sequenceNumber = null;

    /**
     * @return string
     */
    public function getMessageId(): string
    {
        return $this->messageId;
    }

    /**
     * @param string $messageId
     *
     * @return PublishMessageResponse
     */
    public function setMessageId(string $messageId): PublishMessageResponse
    {
        $this->messageId = $messageId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSequenceNumber(): ?string
    {
        return $this->sequenceNumber;
    }

    /**
     * @param string|null $sequenceNumber
     *
     * @return PublishMessageResponse
     */
    public function setSequenceNumber(?string $sequenceNumber): PublishMessageResponse
    {
        $this->sequenceNumber = $sequenceNumber;

        return $this;
    }
}
