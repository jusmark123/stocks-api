<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Protocol;

interface CredentialHandler
{
    public function getCredentials($token): Credentials;
}
