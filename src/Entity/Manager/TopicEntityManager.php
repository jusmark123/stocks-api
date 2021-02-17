<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\Topic;

class TopicEntityManager extends EntityManager
{
    const ENTITY_CLASS = Topic::class;

    const TOPIC_NOT_FOUND = 'Topic not found';
}
