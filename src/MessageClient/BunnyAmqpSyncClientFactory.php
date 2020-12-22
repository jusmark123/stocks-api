<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient;

use App\MessageClient\Protocol\Credentials;
use Bunny\Client;

/**
 * Class BunnyAmqpSyncClientFactory.
 */
class BunnyAmqpSyncClientFactory extends AbstractRabbitAmqpClientFactory implements SyncClientFactory
{
    /**
     * @param Credentials|null $credentials
     *
     * @return Client
     */
    public function createClient(?Credentials $credentials = null): Client
    {
        $options = $this->mungOptions($credentials);

        return new Client($options);
    }
}
