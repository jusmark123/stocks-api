<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca\Order;

/**
 * Class StopLoss.
 */
class StopLoss
{
    /**
     * @var float
     */
    private float $stopPrice;

    /**
     * @var float
     */
    private float $limitPrice;

    /**
     * @return float
     */
    public function getStopPrice(): float
    {
        return $this->stopPrice;
    }

    /**
     * @param string $stopPrice
     *
     * @return StopLoss
     */
    public function setStopPrice(string $stopPrice): StopLoss
    {
        $this->stopPrice = (float) $stopPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getLimitPrice(): float
    {
        return $this->limitPrice;
    }

    /**
     * @param float $limitPrice
     *
     * @return StopLoss
     */
    public function setLimitPrice(float $limitPrice): StopLoss
    {
        $this->limitPrice = $limitPrice;

        return $this;
    }
}
