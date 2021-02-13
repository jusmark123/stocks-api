<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca\Order;

class StopLoss
{
    /**
     * @var string
     */
    private $stopPrice;

    /**
     * @var string
     */
    private $limitPrice;

    /**
     * @return string
     */
    public function getStopPrice(): string
    {
        return $this->stopPrice;
    }

    /**
     * @param float $stopPrice
     *
     * @return StopLoss
     */
    public function setStopPrice(float $stopPrice): StopLoss
    {
        $this->stopPrice = $stopPrice;

        return $this;
    }

    /**
     * @return string
     */
    public function getLimitPrice(): string
    {
        return $this->limitPrice;
    }

    /**
     * @param float $limitPrice
     *
     * @return AlpacaLimitPrice
     */
    public function setLimitPrice(float $limitPrice): AlpacaLimitPrice
    {
        $this->limitPrice = $limitPrice;

        return $this;
    }
}
