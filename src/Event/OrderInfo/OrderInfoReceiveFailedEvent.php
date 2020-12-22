<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\Event\AbstractJobFailedEvent;

/**
 * Class OrderInfoReceiveFailedEvent.
 */
class OrderInfoReceiveFailedEvent extends AbstractJobFailedEvent implements OrderInfoFailedEventInterface
{
    use OrderInfoFailedEventTrait;

    const EVENT_NAME = 'order-id.receive';
}
