<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\Stream;

/**
 * Class StreamEntityManager.
 */
class StreamEntityManager extends AbstractEntityManager
{
    const ENTITY_CLASS = Stream::class;
}
