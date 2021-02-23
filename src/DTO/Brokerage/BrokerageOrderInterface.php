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
 * Interface BrokerageOrderInterface.
 */
interface BrokerageOrderInterface
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
     * @return BrokerageOrderInterface
     */
    public function setAccount(Account $account): BrokerageOrderInterface;

    /**
     * @return Source
     */
    public function getSource(): Source;

    /**
     * @param Source $source
     *
     * @return BrokerageOrderInterface
     */
    public function setSource(Source $source): BrokerageOrderInterface;

    /**
     * @return Order|null
     */
    public function getOrder(): ?Order;

    /**
     * @param Order $order
     *
     * @return BrokerageOrderInterface
     */
    public function setOrder(Order $order): BrokerageOrderInterface;

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime;
}
