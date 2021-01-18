<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Aws\Sns;

use App\Entity\AbstractGuidEntity;
use App\Entity\Topic;

class SubscriptionRequest extends AbstractGuidEntity
{
    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var string|null
     */
    private $endpoint = null;

    /**
     * @var string
     */
    private $protocol = 'http';

    /**
     * @var bool
     */
    private $returnSubscriptionArn = true;

    /**
     * @var Topic|null
     */
    private $topic = null;

    /**
     * @var string|null
     */
    private $topicArn = null;

    /**
     * SubscriptionRequest constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
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
     * @return SubscriptionRequest
     */
    public function setAttributes(array $attributes): SubscriptionRequest
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    /**
     * @param string $endpoint
     *
     * @return SubscriptionRequest
     */
    public function setEndpoint(?string $endpoint = null): SubscriptionRequest
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->protocol;
    }

    /**
     * @param string $protocol
     *
     * @return SubscriptionRequest
     */
    public function setProtocol(string $protocol): SubscriptionRequest
    {
        $this->protocol = $protocol;

        return $this;
    }

    /**
     * @return bool
     */
    public function shouldReturnSubscriptionArn(): bool
    {
        return $this->returnSubscriptionArn;
    }

    /**
     * @param bool $returnSubscriptionArn
     *
     * @return SubscriptionRequest
     */
    public function setReturnSubscriptionArn(bool $returnSubscriptionArn): SubscriptionRequest
    {
        $this->returnSubscriptionArn = $returnSubscriptionArn;

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
     * @return SubscriptionRequest
     */
    public function setTopic(?Topic $topic): SubscriptionRequest
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTopicArn(): ?string
    {
        return $this->topicArn;
    }

    /**
     * @param string|null $topicArn
     *
     * @return SubscriptionRequest
     */
    public function setTopicArn(?string $topicArn): SubscriptionRequest
    {
        $this->topicArn = $topicArn;

        return $this;
    }
}
