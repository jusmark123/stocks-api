<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Client\Stream;

/**
 * Interface StreamHandlerProvider.
 */
interface StreamHandlerProvider
{
    /**
     * @return mixed
     */
    public function registerHandlers();

    /**
     * @return mixed
     */
    public function getHandlers();
}
