<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Bridge\Symfony\Messenger\Message;

use App\MessageClient\Bridge\Symfony\Messenger\Message\Traits\MessageAwareTrait;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class AbstractMessage.
 */
abstract class AbstractMessage implements MessageAwareInterface
{
    use MessageAwareTrait;

    /**
     * AbstractMessage constructor.
     *
     * @param UuidInterface|null $identifier
     */
    public function __construct(UuidInterface $identifier = null)
    {
        $this->setIdentifier($identifier ?? Uuid::uuid4());
    }
}
