<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient;

use App\MessageClient\Protocol\Credentials;
use React\Promise\ExtendedPromiseInterface;

/**
 * Interface ClientFactory.
 */
interface ClientFactory
{
    /**
     * @param Credentials $credentials
     *
     * @return ExtendedPromiseInterface
     */
    public function createClient(Credentials $credentials): ExtendedPromiseInterface;
}
