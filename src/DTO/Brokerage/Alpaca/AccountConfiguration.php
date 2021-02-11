<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca;

class AccountConfiguration
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
     * @return AccountConfiguration
     */
    public function setDtbpCheck(string $dtbpCheck): AccountConfiguration
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
     * @return AccountConfiguration
     */
    public function setNoShorting(bool $noShorting): AccountConfiguration
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
     * @return AccountConfiguration
     */
    public function setSuspendTrade(bool $suspendTrade): AccountConfiguration
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
     * @return AccountConfiguration|null
     */
    public function setTradeConfirmationEmail(?string $tradeConfirmationEmail): AccountConfiguration
    {
        $this->tradeConfirmationEmail = $tradeConfirmationEmail;

        return $this;
    }
}
