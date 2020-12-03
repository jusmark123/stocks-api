<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Protocol\FrameType;

class SubscribeNackType extends BaseType
{
    const TYPE = 'SUBSCRIBE_NACK';
}
