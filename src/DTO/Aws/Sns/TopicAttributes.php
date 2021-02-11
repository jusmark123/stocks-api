<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Aws\Sns;

use App\Entity\AbstractDefaultEntity;

/**
 * Class TopicAttributes.
 */
class TopicAttributes extends AbstractDefaultEntity
{
    /**
     * @var string
     */
    private $displayName;

    /**
     * @var string
     */
    private $effectiveDeliveryPolicy;

    /**
     * @var string
     */
    private $owner;

    /**
     * @var string
     */
    private $policy;

    /**
     * @var string
     */
    private $subscriptionsConfirmed;

    /**
     * @var string
     */
    private $subscriptionsDeleted;

    /**
     * @var string
     */
    private $subscriptionsPending;

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     *
     * @return TopicAttributes
     */
    public function setDisplayName(string $displayName): TopicAttributes
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * @return array
     */
    public function getEffectiveDeliveryPolicy(): array
    {
        return json_decode($this->effectiveDeliveryPolicy, true);
    }

    /**
     * @param string $effectiveDeliveryPolicy
     *
     * @return TopicAttributes
     */
    public function setEffectiveDeliveryPolicy(string $effectiveDeliveryPolicy): TopicAttributes
    {
        $this->effectiveDeliveryPolicy = $effectiveDeliveryPolicy;

        return $this;
    }

    /**
     * @return string
     */
    public function getOwner(): string
    {
        return $this->owner;
    }

    /**
     * @param string $owner
     *
     * @return TopicAttributes
     */
    public function setOwner(string $owner): TopicAttributes
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return array
     */
    public function getPolicy(): array
    {
        return json_decode($this->policy, true);
    }

    /**
     * @param string $policy
     *
     * @return TopicAttributes
     */
    public function setPolicy(string $policy): TopicAttributes
    {
        $this->policy = $policy;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubscriptionsConfirmed(): string
    {
        return $this->subscriptionsConfirmed;
    }

    /**
     * @param string $subscriptionsConfirmed
     *
     * @return TopicAttributes
     */
    public function setSubscriptionsConfirmed(string $subscriptionsConfirmed): TopicAttributes
    {
        $this->subscriptionsConfirmed = $subscriptionsConfirmed;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubscriptionsDeleted(): string
    {
        return $this->subscriptionsDeleted;
    }

    /**
     * @param string $subscriptionsDeleted
     *
     * @return TopicAttributes
     */
    public function setSubscriptionsDeleted(string $subscriptionsDeleted): TopicAttributes
    {
        $this->subscriptionsDeleted = $subscriptionsDeleted;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubscriptionsPending(): string
    {
        return $this->subscriptionsPending;
    }

    /**
     * @param string $subscriptionsPending
     *
     * @return TopicAttributes
     */
    public function setSubscriptionsPending(string $subscriptionsPending): TopicAttributes
    {
        $this->subscriptionsPending = $subscriptionsPending;

        return $this;
    }
}
