<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Protocol;

interface Packet extends Topic
{
    /**
     * @return mixed
     */
    public function getMessage();

    /**
     * @param mixed $data
     *
     * @return Packet
     */
    public function setMessage($data): Packet;

    /**
     * @return mixed
     */
    public function getOriginalMessage();

    /**
     * @param $message
     *
     * @return Packet
     */
    public function setOriginalMessage($message): Packet;
}
