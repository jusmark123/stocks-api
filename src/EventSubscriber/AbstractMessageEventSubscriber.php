<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractMessageEventSubscriber extends AbstractEventSubscriber
{
    const ENTITY_LOG_FORMAT = 'json';

    use SerializerAwareTrait;
    use LoggerAwareTrait;

    public function __construct(
        LoggerInterface $logger,
        SerializerInterface $serializer
    ) {
        $this->setLogger($logger);
        $this->setSerializer($serializer);
    }
}
