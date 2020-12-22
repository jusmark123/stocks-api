<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Protocol;

use App\MessageClient\Exception\InvalidFrameType;

class FrameTypeCollection
{
    protected $frames;

    public function __construct(array $frames)
    {
        $this->frames = $frames;
    }

    public static function create($from)
    {
        if ($from instanceof self) {
            return $from;
        }

        $self = new self([]);

        try {
            foreach ($from as $item) {
                $self->addFrame($item);
            }
        } catch (\TypeError $e) {
            throw new InvalidFrameType(
              'Invalid Frame type given',
              0,
              $e
            );
        }

        return $self;
    }

    public function getFrames(): array
    {
        return $this->frames;
    }

    public function setFrames(array $frames): FrameTypeCollection
    {
        $this->frames = [];
        foreach ($frames as $frame) {
            $this->addFrame($frame);
        }

        return $this;
    }

    public function addFrame(FrameType $frame): FrameTypeCollection
    {
        $this->frames[$frame->getType()] = $frame;

        return $this;
    }
}
