<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\DTO\Brokerage\Interfaces\OrderInfoInterface;

interface OrderInfoFailedEventInterface
{
    public function getOrderInfo(): ?OrderInfoInterface;

    public function getOrderInfoMessage(): ?array;
}
