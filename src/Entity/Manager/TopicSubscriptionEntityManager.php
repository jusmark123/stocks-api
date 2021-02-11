<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\TopicSubscription;

class TopicSubscriptionEntityManager extends AbstractEntityManager
{
    const ENTITY_CLASS = TopicSubscription::class;

    const TOPIC_SUBSCRIPTION_NOT_FOUND = 'SubscriptionItemDataProvider not found';
}
