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

    public function setId(string $id): OrderInfoInterface;

    public function getAccount(): Account;

    public function setAccount(Account $account): OrderInfoInterface;

    public function getOrder(): ?Order;

    public function setOrder(Order $order): OrderInfoInterface;

    public function getStatus(): string;

    public function setStatus(string $status): OrderInfoInterface;

    public function getCreatetAt(): \DateTime;

    public function setCreatetAt(string $createdAt): OrderInfoInterface;
}
