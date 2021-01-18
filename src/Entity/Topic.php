<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use App\DTO\Aws\Sns\Notification;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Topic.
 *
 * @ORM\Table(
 *     name="topic",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="topic_un_guid", columns={"guid"}),
 *     },
 *     indexes={
 *          @ORM\Index(name="topic_ix_topic_arn", columns={"topic_arn"}),
 *     },
 * )
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class Topic extends AbstractGuidEntity
{
    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="topic_arn", type="string", length=255, nullable=false)
     */
    private $topicArn;

    /**
     * @var ArrayCollection|TopicSubscription[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="TopicSubscription", mappedBy="topic", cascade={"persist", "remove"})
     */
    private $subscriptions;

    /**
     * @var ArrayCollection|Notification[]
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
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     *
     * @return Topic
     */
    public function setAttributes(array $attributes): Topic
    {
        $this->attributes = $attributes;

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
     * @return TopicSubscription[]|ArrayCollection|PersistentCollection
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * @param TopicSubscription[]|ArrayCollection|PersistentCollection $subscriptions
     *
     * @return Topic
     */
    public function setSubscriptions($subscriptions): Topic
    {
        $this->subscriptions = $subscriptions;

        return $this;
    }

    /**
     * @return Notification[]|ArrayCollection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @param Notification[]|ArrayCollection $notifications
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
