<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Bridge\Symfony\Messenger\Message;

use App\MessageClient\Bridge\Symfony\Messenger\Message\Traits\EntityMessageAwareTrait;
use Ramsey\Uuid\UuidInterface;

/**
 * Class AbstractEntityMessage.
 */
abstract class AbstractEntityMessage extends AbstractMessage implements EntityMessageAwareInterface
{
    use EntityMessageAwareTrait {
        EntityMessageAwareTrait::__construct as private __entityMessageConstructor;
    }

    /**
     * AbstractEntityMessage constructor.
     *
     * @param string        $entityClass
     * @param UuidInterface $entityIdentitfer
     * @param UuidInterface $identifier
     */
    public function __construct(string $entityClass, UuidInterface $entityIdentitfer, UuidInterface $identifier)
    {
        $this->__entityMessageConstructor($entityClass, $entityIdentitfer);
        parent::__construct($identifier);
    }
}
