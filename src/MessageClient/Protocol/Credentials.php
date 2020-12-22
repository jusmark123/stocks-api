<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Protocol;

interface Credentials
{
    public function getCredentials(): array;
}
