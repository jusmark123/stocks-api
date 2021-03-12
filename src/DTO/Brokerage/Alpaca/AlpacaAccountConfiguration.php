<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca;

class AlpacaAccountConfiguration
{
    /**
     * @var string
     */
    private $dtbpCheck;

    /**
     * @var bool
     */
    private $noShorting;

    /**
     * @var bool
     */
    private $suspendTrade;

    /**
     * @var string|null
     */
    private $tradeConfirmationEmail = null;

    /**
     * @return string
     */
    public function getDtbpCheck(): string
    {
        return $this->dtbpCheck;
    }

    /**
     * @param string $dtbpCheck
     *
     * @return AlpacaAccountConfiguration
     */
    public function setDtbpCheck(string $dtbpCheck): AlpacaAccountConfiguration
    {
        $this->dtbpCheck = $dtbpCheck;

        return $this;
    }

    /**
     * @return bool
     */
    public function isNoShorting(): bool
    {
        return $this->noShorting;
    }

    /**
     * @param bool $noShorting
     *
     * @return AlpacaAccountConfiguration
     */
    public function setNoShorting(bool $noShorting): AlpacaAccountConfiguration
    {
        $this->noShorting = $noShorting;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSuspendTrade(): bool
    {
        return $this->suspendTrade;
    }

    /**
     * @param bool $suspendTrade
     *
     * @return AlpacaAccountConfiguration
     */
    public function setSuspendTrade(bool $suspendTrade): AlpacaAccountConfiguration
    {
        $this->suspendTrade = $suspendTrade;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTradeConfirmationEmail(): ?string
    {
        return $this->tradeConfirmationEmail;
    }

    /**
     * @param string $tradeConfirmationEmail
     *
     * @return AlpacaAccountConfiguration|null
     */
    public function setTradeConfirmationEmail(?string $tradeConfirmationEmail): AlpacaAccountConfiguration
    {
        $this->tradeConfirmationEmail = $tradeConfirmationEmail;

        return $this;
    }
}
