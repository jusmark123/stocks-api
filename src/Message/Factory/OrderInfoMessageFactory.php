<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Factory;

use App\DTO\Brokerage\Interfaces\OrderInfoInterface;

class OrderInfoMessageFactory extends AbstractMessageFactory
{
    /**
     * @param array $message
     *
     * @return OrderInfoInterface
     */
    public function createFromMessage(array $message): OrderInfoInterface
    {
        return $this->createFromReceivedMessage($message, OrderInfoInterface::class);
    }
}
