<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca\Order;

class AlpacaStopLoss
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
     * @return AlpacaStopLoss
     */
    public function setStopPrice(float $stopPrice): AlpacaStopLoss
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
