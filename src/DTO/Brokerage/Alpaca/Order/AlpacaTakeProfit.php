<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca\Order;

class AlpacaTakeProfit
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
     * @return AlpacaTakeProfit
     */
    public function setLimitPrice(float $limitPrice): AlpacaTakeProfit
    {
        $this->limitPrice = $limitPrice;

        return $this;
    }
}
