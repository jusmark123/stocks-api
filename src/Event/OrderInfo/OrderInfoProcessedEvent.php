<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\Event\AbstractJobEvent;

/**
 * Class OrderInfoProcessedEvent.
 */
class OrderInfoProcessedEvent extends AbstractJobEvent
{
    use OrderInfoEventTrait;

    const EVENT_NAME = 'order-info.processed';
}
