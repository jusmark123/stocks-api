<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use App\DTO\Aws\Sns\Notification;
use App\DTO\Aws\Sns\TopicAttributes;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Topic.
 */
class Topic extends AbstractGuidEntity
{
    /**
     * @var TopicAttributes
     */
    private $attributes = [];

    /**
     * @var bool
     */
    private $contentBasedDeduplication;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $topicArn;

    /**
     * @var string
     */
    private $type;

    /**
     * @var TopicSubscription[]
     */
    private $subscriptions;

    /**
     * @var Notification[]
     */
    private $notifications;

    /**
     * @var array|null
     */
    private $tags;

    /**
     * Topic constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->notifications = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
        $this->tags = [];
    }

    /**
     * @return TopicAttributes
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param TopicAttributes $attributes
     *
     * @return Topic
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @return bool
     */
    public function isContentBasedDeduplication(): bool
    {
        return $this->contentBasedDeduplication;
    }

    /**
     * @param bool $contentBasedDeduplication
     *
     * @return Topic
     */
    public function setContentBasedDeduplication(bool $contentBasedDeduplication): Topic
    {
        $this->contentBasedDeduplication = $contentBasedDeduplication;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Topic
     */
    public function setName(string $name): Topic
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Topic
     */
    public function setType(string $type): Topic
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getTopicArn(): string
    {
        return $this->topicArn;
    }

    /**
     * @param string $topicArn
     *
     * @return Topic
     */
    public function setTopicArn(string $topicArn): Topic
    {
        $this->topicArn = $topicArn;

        return $this;
    }

    /**
     * @return TopicSubscription[]
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * @param TopicSubscription[] $subscriptions
     *
     * @return Topic
     */
    public function setSubscriptions($subscriptions): Topic
    {
        $this->subscriptions = $subscriptions;

        return $this;
    }

    /**
     * @return Notification[]
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @param Notification[] $notifications
     *
     * @return Topic
     */
    public function setNotifications($notifications): Topic
    {
        $this->notifications = $notifications;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getTags(): ?array
    {
        return $this->tags;
    }

    /**
     * @param array|null $tags
     *
     * @return Topic
     */
    public function setTags(?array $tags = []): Topic
    {
        $this->tags = $tags;

        return $this;
    }
}
