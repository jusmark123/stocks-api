<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca\Order;

class TakeProfit
{
    /**
     * @var string
     */
    private $limitPrice;

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
     * @return TakeProfit
     */
    public function setLimitPrice(float $limitPrice): TakeProfit
    {
        $this->limitPrice = $limitPrice;

        return $this;
    }
}
