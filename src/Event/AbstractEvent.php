<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class AbstractEvent.
 */
abstract class AbstractEvent extends Event
{
    const EVENT_DOMAIN = 'stocks-api.receiver';

    const EVENT_NAME = '';

    /**
     * @return string
     */
    public static function getEventName()
    {
        return static::EVENT_DOMAIN.static::EVENT_NAME;
    }
}
