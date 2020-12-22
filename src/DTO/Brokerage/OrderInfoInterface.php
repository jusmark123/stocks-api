<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage;

use App\Entity\Account;
use App\Entity\Order;
use App\Entity\Source;

/**
 * Interface OrderInfoInterface.
 */
interface OrderInfoInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return Account
     */
    public function getAccount(): Account;

    /**
     * @param Account $account
     *
     * @return OrderInfoInterface
     */
    public function setAccount(Account $account): OrderInfoInterface;

    /**
     * @return Source
     */
    public function getSource(): Source;

    /**
     * @param Source $source
     *
     * @return OrderInfoInterface
     */
    public function setSource(Source $source): OrderInfoInterface;

    /**
     * @return Order|null
     */
    public function getOrder(): ?Order;

    /**
     * @param Order $order
     *
     * @return OrderInfoInterface
     */
    public function setOrder(Order $order): OrderInfoInterface;

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime;
}
