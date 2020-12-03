<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\Source;

/**
 * Class SourceFactory.
 */
class SourceFactory
{
    /**
     * @return Source
     */
    public static function create(): Source
    {
        return new Source();
    }
}
