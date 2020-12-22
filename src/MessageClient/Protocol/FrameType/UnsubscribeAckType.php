<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Protocol\FrameType;

class UnsubscribeAckType extends BaseType
{
    const TYPE = 'UNSUBSCRIBE_ACK';
}
