<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\Topic;

class TopicFactory
{
    public static function create(): Topic
    {
        return new Topic();
    }
}
