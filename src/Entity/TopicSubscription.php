<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class TopicSubscription.
 */
class TopicSubscription extends AbstractGuidEntity
{
    /**
     * @var array
     */
    private $attributes;

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
    private $confirmed = false;

    /**
     * @var string|null
     */
    private $subscriptionArn;

    /**
     * @var Topic
     */
    private $topic;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return UuidInterface
     */
    public function getGuid(): UuidInterface
    {
        if (null !== $this->subscriptionArn) {
            $parts = explode(':', $this->subscriptionArn);

            if (6 === \count($parts)) {
                $this->guid = Uuid::fromString(end($parts));
            }
        }

        return $this->guid;
    }

    /**
     * @return string|null
     */
    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    /**
     * @param string|null $endpoint
     *
     * @return TopicSubscription
     */
    public function setEndpoint(?string $endpoint): TopicSubscription
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
     * @return TopicSubscription
     */
    public function setProtocol(string $protocol): TopicSubscription
    {
        $this->protocol = $protocol;

        return $this;
    }

    /**
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    /**
     * @param bool $confirmed
     *
     * @return TopicSubscription
     */
    public function setConfirmed(bool $confirmed): TopicSubscription
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubscriptionArn(): ?string
    {
        return $this->subscriptionArn;
    }

    /**
     * @param string|null $subscriptionArn
     *
     * @return TopicSubscription
     */
    public function setSubscriptionArn(?string $subscriptionArn): TopicSubscription
    {
        $this->subscriptionArn = $subscriptionArn;

        return $this;
    }

    /**
     * @return Topic
     */
    public function getTopic(): Topic
    {
        return $this->topic;
    }

    /**
     * @param Topic $topic
     *
     * @return TopicSubscription
     */
    public function setTopic(Topic $topic): TopicSubscription
    {
        $this->topic = $topic;

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
     * @return TopicSubscription
     */
    public function setAttributes(array $attributes): TopicSubscription
    {
        $this->attributes = $attributes;

        return $this;
    }
}
