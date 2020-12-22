<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Interfaces;

use App\Entity\Account;
use App\Entity\Order;

interface OrderInfoInterface
{
    public function getId(): string;

    public function getAccount(): Account;

    public function setAccount(Account $account): OrderInfoInterface;

    public function getOrder(): ?Order;

    public function setOrder(Order $order): OrderInfoInterface;

    public function getStatus(): string;

    public function getCreatedAt(): \DateTime;
}
