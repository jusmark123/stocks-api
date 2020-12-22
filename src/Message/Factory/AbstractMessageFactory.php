<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Factory;

use App\Helper\SerializerHelper;

abstract class AbstractMessageFactory
{
    public function createFromReceivedMessage(array $message, string $class)
    {
        return SerializerHelper::ObjectNormalizer()->deserialize($message, $class, null);
    }
}
