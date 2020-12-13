<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Factory;

use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Job;

class OrderInfoMessageFactory extends AbstractMessageFactory
{
    /**
     * @param array    $message
     * @param Job|null $job
     *
     * @return array|mixed|object
     */
    public function createFromMessage(array $message, ?Job $job = null)
    {
        return $this->createFromReceivedMessage($message, OrderInfoInterface::class);
    }
}
