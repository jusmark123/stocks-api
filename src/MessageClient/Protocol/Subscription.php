<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Protocol;

interface Subscription
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param mixed $id
     *
     * @return Subscription
     */
    public function setId($id = null): Subscription;

    /**
     * @return string
     */
    public function getCallback(): string;

    /**
     * @param string $uri
     *
     * @return Subscription
     */
    public function setCallback(string $uri): Subscription;
}
