<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Protocol\FrameType;

class PublishNackType extends BaseType
{
    const TYPE = 'PUBLISH_NACK';
}
