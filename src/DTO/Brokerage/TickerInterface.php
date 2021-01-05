<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage;

/**
 * Class TickerInterface.
 */
interface TickerInterface
{
    /**
     * @return float
     */
    public function getCurrency(): string;

    /**
     * @return string|null
     */
    public function getExchange(): ?string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getMarket(): string;

    /**
     * @return string
     */
    public function getTicker(): string;

    /**
     * @return bool
     */
    public function isActive(): bool;

    /**
     * @return string|null
     */
    public function getType(): ?string;
}
