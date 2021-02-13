<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Aws\Sns;

use App\Entity\AbstractGuidEntity;
use App\Entity\Topic;

/**
 * Class PublishMessageRequest.
 */
class PublishMessageRequest extends AbstractGuidEntity
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var string|null
     */
    private $deduplicationId = null;

    /**
     * @var string|null
     */
    private $groupId = null;

    /**
     * @var string
     */
    private $structure = 'json';

    /**
     * @var string|null
     */
    private $phoneNumber = null;

    /**
     * @var string|null
     */
    private $subject = null;

    /**
     * @var string|null
     */
    private $targetArn = null;

    /**
     * @var Topic|null
     */
    private $topic = null;

    /**
     * PublishMessageRequest constructor.
     */
    public function __construct()
    {
        $this->attributes = [];
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return PublishMessageRequest
     */
    public function setMessage(string $message): PublishMessageRequest
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     *
     * @return PublishMessageRequest
     */
    public function setAttributes(array $attributes): PublishMessageRequest
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDeduplicationId(): ?string
    {
        return $this->deduplicationId;
    }

    /**
     * @param string|null $deduplicationId
     *
     * @return PublishMessageRequest
     */
    public function setDeduplicationId(?string $deduplicationId): PublishMessageRequest
    {
        $this->deduplicationId = $deduplicationId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getGroupId(): ?string
    {
        return $this->groupId;
    }

    /**
     * @param string|null $groupId
     *
     * @return PublishMessageRequest
     */
    public function setGroupId(?string $groupId): PublishMessageRequest
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * @return string
     */
    public function getStructure(): string
    {
        return $this->structure;
    }

    /**
     * @param string $structure
     *
     * @return PublishMessageRequest
     */
    public function setStructure(string $structure): PublishMessageRequest
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string|null $phoneNumber
     *
     * @return PublishMessageRequest
     */
    public function setPhoneNumber(?string $phoneNumber): PublishMessageRequest
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param string|null $subject
     *
     * @return PublishMessageRequest
     */
    public function setSubject(?string $subject): PublishMessageRequest
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTargetArn(): ?string
    {
        return $this->targetArn;
    }

    /**
     * @param string|null $targetArn
     *
     * @return PublishMessageRequest
     */
    public function setTargetArn(?string $targetArn): PublishMessageRequest
    {
        $this->targetArn = $targetArn;

        return $this;
    }

    /**
     * @return Topic|null
     */
    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    /**
     * @param Topic|null $topic
     *
     * @return PublishMessageRequest
     */
    public function setTopic(?Topic $topic): PublishMessageRequest
    {
        $this->topic = $topic;

        return $this;
    }
}
