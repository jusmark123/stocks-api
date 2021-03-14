<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage;

/**
 * Interface BrokerageOrderEventInterface.
 */
interface BrokerageOrderEventInterface
{
    /**
     * @return string
     */
    public function getEvent(): string;

    /**
     * @param string $event
     *
     * @return $this
     */
    public function setEvent(string $event): self;

    /**
     * @return float
     */
    public function getPrice(): float;

    /**
     * @param string|float $price
     *
     * @return $this
     */
    public function setPrice($price = 0.00): self;

    /**
     * @return \DateTime
     */
    public function getTimeStamp(): \DateTime;

    /**
     * @param string|\DateTime $timestamp
     *
     * @return $this
     */
    public function setTimeStamp($timestamp): self;

    /**
     * @return BrokerageOrderInterface
     */
    public function getOrder(): BrokerageOrderInterface;

    /**
     * @param BrokerageOrderInterface $order
     *
     * @return $this
     */
    public function setOrder(BrokerageOrderInterface $order): self;

    /**
     * @return float
     */
    public function getQuantity(): float;

    /**
     * @param string|float $quantity
     *
     * @return $this
     */
    public function setQuantity($quantity): self;
}
