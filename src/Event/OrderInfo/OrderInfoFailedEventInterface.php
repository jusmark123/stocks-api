<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Job;

interface OrderInfoFailedEventInterface
{
    public function getJob(): Job;

    public function getOrderInfo(): ?OrderInfoInterface;

    public function getOrderInfoMessage(): ?array;

    public function getException(): \Exception;
}
