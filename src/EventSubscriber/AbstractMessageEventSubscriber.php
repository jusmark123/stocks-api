<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractMessageEventSubscriber extends AbstractEventSubscriber
{
    const ENTITY_LOG_FORMAT = 'json';

    use LoggerAwareTrait;
    use SerializerAwareTrait;

    /** @var EventDispatcherInterface
     *
     */
    protected $dispatcher;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger,
        SerializerInterface $serializer
    ) {
        $this->dispatcher = $dispatcher;
        $this->setLogger($logger);
        $this->setSerializer($serializer);
    }
}
