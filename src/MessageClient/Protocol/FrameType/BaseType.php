<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Protocol\FrameType;

use App\MessageClient\Exception\InvalidFrameType;
use App\MessageClient\Protocol\FrameType;

abstract class BaseType implements FrameType
{
    const TYPE = false;

    /**
     * @return [type]
     */
    public function toString()
    {
        return $this->__toString();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->getType();
        } catch (InvalidFrameType $e) {
            return 'borked';
        }
    }

    /**
     * @throws InvalidFrameType
     *
     * @return string
     */
    public function getType(): string
    {
        if (empty(static::TYPE)) {
            throw new InvalidFrameType('frame type must have a TYPE constant');
        }

        return static::TYPE;
    }
}
