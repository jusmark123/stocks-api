<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\ClientListener;

/**
 * Interface ClientListenerProvider.
 */
interface ClientListenerProvider
{
    /**
     * @return mixed
     */
    public function registerListeners();

    /**
     * @return array
     */
    public function getListeners(): array;
}
