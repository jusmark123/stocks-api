<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca;

use App\DTO\Brokerage\AccountInfoInterface;
use App\Entity\Account;

class AlpacaAccountInfo implements AccountInfoInterface
{
    /**
     * @var Account
     */
    private $account;

    /**
     * @var bool
     */
    private $accountBlocked;

    /**
     * @var string
     */
    private $accountNumber;

    /**
     * @var float
     */
    private $buyingPower;

    /**
     * @var float
     */
    private $cash;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var int
     */
    private $daytradeCount;

    /**
     * @var float
     */
    private $daytradeBuyingPower;

    /**
     * @var float
     */
    private $equity;

    /**
     * @var string
     */
    private $id;

    /**
     * @var float
     */
    private $initialMargin;

    /**
     * @var float
     */
    private $lastEquity;

    /**
     * @var float
     */
    private $lastMaintenanceMargin;

    /**
     * @var float
     */
    private $longMarketValue;

    /**
     * @var float
     */
    private $maintenanceMargin;

    /**
     * @var int
     */
    private $multiplier;

    /**
     * @var bool
     */
    private $patternDayTrader;

    /**
     * @var float
     */
    private $portfolioValue;

    /**
     * @var float
     */
    private $regtBuyingPower;

    /**
     * @var float
     */
    private $shortMarketValue;

    /**
     * @var bool
     */
    private $shortingEnabled;

    /**
     * @var int
     */
    private $sma;

    /**
     * @var string
     */
    private $status;

    /**
     * @var bool
     */
    private $tradeSuspendedByUser;

    /**
     * @var bool
     */
    private $tradingBlocked;

    /**
     * @var bool
     */
    private $transfersBlocked;

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     *
     * @return AlpacaAccountInfo
     */
    public function setAccount(Account $account): AlpacaAccountInfo
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAccountBlocked(): bool
    {
        return $this->accountBlocked;
    }

    /**
     * @param bool $accountBlocked
     *
     * @return AlpacaAccountInfo
     */
    public function setAccountBlocked(bool $accountBlocked): AlpacaAccountInfo
    {
        $this->accountBlocked = $accountBlocked;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    /**
     * @param string $accountNumber
     *
     * @return AlpacaAccountInfo
     */
    public function setAccountNumber(string $accountNumber): AlpacaAccountInfo
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * @return float
     */
    public function getBuyingPower(): float
    {
        return $this->buyingPower;
    }

    /**
     * @param string $buyingPower
     *
     * @return $this
     */
    public function setBuyingPower(string $buyingPower): AlpacaAccountInfo
    {
        $this->buyingPower = (float) $buyingPower;

        return $this;
    }

    /**
     * @return float
     */
    public function getCash(): float
    {
        return $this->cash;
    }

    /**
     * @param string $cash
     *
     * @return $this
     */
    public function setCash(string $cash): AlpacaAccountInfo
    {
        $this->cash = (float) $cash;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function setCreatedAt(string $createdAt): AlpacaAccountInfo
    {
        $this->createdAt = new \DateTime($createdAt);

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return AlpacaAccountInfo
     */
    public function setCurrency(string $currency): AlpacaAccountInfo
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return int
     */
    public function getDaytradeCount(): int
    {
        return $this->daytradeCount;
    }

    /**
     * @param int $daytradeCount
     *
     * @return AlpacaAccountInfo
     */
    public function setDayTradeCount(int $daytradeCount): AlpacaAccountInfo
    {
        $this->daytradeCount = $daytradeCount;

        return $this;
    }

    /**
     * @return float
     */
    public function getDaytradeBuyingPower(): float
    {
        return $this->daytradeBuyingPower;
    }

    /**
     * @param string $daytradeBuyingPower
     *
     * @return $this
     */
    public function setDaytradeBuyingPower(string $daytradeBuyingPower): AlpacaAccountInfo
    {
        $this->daytradeBuyingPower = (float) $daytradeBuyingPower;

        return $this;
    }

    /**
     * @return float
     */
    public function getEquity(): float
    {
        return $this->equity;
    }

    /**
     * @param string $equity
     *
     * @return $this
     */
    public function setEquity(string $equity): AlpacaAccountInfo
    {
        $this->equity = (float) $equity;

        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return AlpacaAccountInfo
     */
    public function setId(string $id): AlpacaAccountInfo
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return float
     */
    public function getInitialMargin(): float
    {
        return $this->initialMargin;
    }

    /**
     * @param string $initialMargin
     *
     * @return $this
     */
    public function setInitialMargin(string $initialMargin): AlpacaAccountInfo
    {
        $this->initialMargin = (float) $initialMargin;

        return $this;
    }

    /**
     * @return float
     */
    public function getLastEquity(): float
    {
        return $this->lastEquity;
    }

    /**
     * @param string $lastEquity
     *
     * @return $this
     */
    public function setLastEquity(string $lastEquity): AlpacaAccountInfo
    {
        $this->lastEquity = (float) $lastEquity;

        return $this;
    }

    /**
     * @return float
     */
    public function getLastMaintenanceMargin(): ?float
    {
        return $this->lastMaintenanceMargin;
    }

    /**
     * @param string $lastMaintenanceMargin
     *
     * @return $this
     */
    public function setLastMaintenaceMargin(string $lastMaintenanceMargin): AlpacaAccountInfo
    {
        $this->lastMaintenanceMargin = (float) $lastMaintenanceMargin;

        return $this;
    }

    /**
     * @return float
     */
    public function getLongMarketValue(): float
    {
        return $this->longMarketValue;
    }

    /**
     * @param string $longMarketValue
     *
     * @return $this
     */
    public function setLongMarketValue(string $longMarketValue): AlpacaAccountInfo
    {
        $this->longMarketValue = (float) $longMarketValue;

        return $this;
    }

    /**
     * @return float
     */
    public function getMaintenanceMargin(): float
    {
        return $this->maintenanceMargin;
    }

    /**
     * @param string $maintenanceMargin
     *
     * @return $this
     */
    public function setMaintenanceMargin(string $maintenanceMargin): AlpacaAccountInfo
    {
        $this->maintenanceMargin = (float) $maintenanceMargin;

        return $this;
    }

    /**
     * @return int
     */
    public function getMultiplier(): int
    {
        return $this->multiplier;
    }

    /**=
     * @param  int $multiplier
     *
     * @return AlpacaAccountInfo
     */
    public function setMultiplier(int $multiplier): AlpacaAccountInfo
    {
        $this->multiplier = $multiplier;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPatternedDayTrader(): bool
    {
        return $this->patternDayTrader;
    }

    /**
     * @param bool $patternDayTrader
     *
     * @return AlpacaAccountInfo
     */
    public function setPatternDaytrader(bool $patternDayTrader): AlpacaAccountInfo
    {
        $this->patternDayTrader = $patternDayTrader;

        return $this;
    }

    /**
     * @return float
     */
    public function getPortfolioValue(): float
    {
        return $this->portfolioValue;
    }

    /**
     * @param string $portfolioValue
     *
     * @return $this
     */
    public function setPortfolioValue(string $portfolioValue): AlpacaAccountInfo
    {
        $this->portfolioValue = (float) $portfolioValue;

        return $this;
    }

    /**
     * @return float
     */
    public function getRegtBuyingPower(): float
    {
        return $this->regtBuyingPower;
    }

    /**
     * @param string $regtBuyingPower
     *
     * @return $this
     */
    public function setRegtBuyingPower(string $regtBuyingPower): AlpacaAccountInfo
    {
        $this->regtBuyingPower = (float) $regtBuyingPower;

        return $this;
    }

    /**
     * @return float
     */
    public function getShortMarketValue(): float
    {
        return $this->shortMarketValue;
    }

    /**
     * @param string $shortMarketValue
     *
     * @return $this
     */
    public function setShortMarketValue(string $shortMarketValue): AlpacaAccountInfo
    {
        $this->shortMarketValue = (float) $shortMarketValue;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShortingEnabled(): bool
    {
        return $this->shortingEnabled;
    }

    /**
     * @param bool $shortingEnabled
     *
     * @return $this
     */
    public function setShortingEnabled(bool $shortingEnabled): AlpacaAccountInfo
    {
        $this->shortingEnabled = $shortingEnabled;

        return $this;
    }

    /**
     * @return int
     */
    public function getSma(): int
    {
        return $this->sma;
    }

    /**
     * @param int $sma
     *
     * @return AlpacaAccountInfo
     */
    public function setSma(int $sma): AlpacaAccountInfo
    {
        $this->sma = $sma;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return AlpacaAccountInfo
     */
    public function setStatus(string $status): AlpacaAccountInfo
    {
        $this->status = $status;

        return $this;
    }

    /**=
     * @return bool
     */
    public function isTradeSuspendedByUser(): bool
    {
        return $this->tradeSuspendedByUser;
    }

    /**
     * @param bool $tradeSuspendedByUser
     *
     * @return AlpacaAccountInfo
     */
    public function setTradeSuspendedByUser(bool $tradeSuspendedByUser): AlpacaAccountInfo
    {
        $this->tradeSuspendedByUser = $tradeSuspendedByUser;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTradingBlocked(): bool
    {
        return $this->tradingBlocked;
    }

    /**
     * @param bool $tradingBlocked
     *
     * @return AlpacaAccountInfo
     */
    public function setTradingBlocked(bool $tradingBlocked): AlpacaAccountInfo
    {
        $this->tradingBlocked = $tradingBlocked;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTransfersBlocked(): bool
    {
        return $this->transfersBlocked;
    }

    /**
     * @param bool $transfersBlocked
     *
     * @return AlpacaAccountInfo
     */
    public function setTransfersBlocked(bool $transfersBlocked): AlpacaAccountInfo
    {
        $this->transfersBlocked = $transfersBlocked;

        return $this;
    }
}
