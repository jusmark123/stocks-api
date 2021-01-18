<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class TopicSubscription.
 *
 * @ORM\Table(
 *   name="topic_subscription",
 *   uniqueConstraints={
 *     @ORM\UniqueConstraint(name="topic_subscription_un_guid", columns={"guid"}),
 *   },
 *   indexes={
 *     @ORM\Index(name="topic_subscription_ix_subscription_arn", columns={"subscription_arn"}),
 *   },
 * )
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class TopicSubscription extends AbstractGuidEntity
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="endpoint", type="string", length=255, nullable=false)
     */
    private $endpoint = null;

    /**
     * @var string
     *
     * @ORM\Column(name="protocol", type="string", length=10, nullable=false)
     */
    private $protocol = 'http';

    /**
     * @var bool
     *
     * @ORM\Column(name="confirmed", type="boolean", nullable=false, options={"default"=false})
     */
    private $confirmed = false;

    /**
     * @var string|null
     *
     * @ORM\Column(name="subscription_arn", type="string", length=255, nullable=true)
     */
    private $subscriptionArn;

    /**
     * @var Topic
     *
     * @ORM\ManyToOne(targetEntity="Topic", inversedBy="subscriptions")
     * @ORM\JoinColumn(name="topic_id", referencedColumnName="id", nullable=false)
     */
    private $topic;

    /**
     * @return string|null
     */
    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    /**
     * @param string $endpoint
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
}
