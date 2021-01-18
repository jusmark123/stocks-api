<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\Topic;
use App\Entity\TopicSubscription;

/**
 * Class TopicSubscriptionFactory.
 */
class TopicSubscriptionFactory
{
    /**
     * @return TopicSubscription
     */
    public static function init(): TopicSubscription
    {
        return new TopicSubscription();
    }

    /**
     * @param Topic $topic
     *
     * @return TopicSubscription
     */
    public static function create(Topic $topic): TopicSubscription
    {
        return self::init()->setTopic($topic);
    }
}
