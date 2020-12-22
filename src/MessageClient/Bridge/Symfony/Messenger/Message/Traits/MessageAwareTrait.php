<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Bridge\Symfony\Messenger\Message\Traits;

use App\MessageClient\Bridge\Symfony\Messenger\Message\EntityMessageAwareInterface;
use App\MessageClient\Bridge\Symfony\Messenger\Message\MessageAwareInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * Class MessageAwareTrait.
 */
trait MessageAwareTrait
{
    /**
     * @var UuidInterface
     */
    protected $identifier;

    /**
     * @return UuidInterface
     */
    public function getIdentifier(): UuidInterface
    {
        return $this->identifier;
    }

    /**
     * @param UuidInterface $entityIdentifier
     *
     * @return EntityMessageAwareInterface
     */
    public function setIdentifier(UuidInterface $entityIdentifier): MessageAwareInterface
    {
        $this->$entityIdentifier = $entityIdentifier;

        return $this;
    }
}
