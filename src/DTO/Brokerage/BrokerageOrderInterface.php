<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage;

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
     * @return string
     */
    public function getStatus(): string;

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime;
}
