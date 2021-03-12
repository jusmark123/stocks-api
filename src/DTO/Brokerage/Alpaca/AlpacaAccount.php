<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca;

use App\Constants\Brokerage\AlpacaConstants;
use App\DTO\Brokerage\AccountInterface;

class AlpacaAccount implements AccountInterface
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var bool
     */
    private bool $accountBlocked;

    /**
     * @var string
     */
    private string $accountNumber;

    /**
     * @var float
     */
    private float $buyingPower;

    /**
     * @var float
     */
    private float $cash;

    /**
     * @var \DateTime
     */
    private \DateTime $createdAt;

    /**
     * @var string
     */
    private string $currency;

    /**
     * @var string
     */
    private string $brokerage;

    /**
     * @var int
     */
    private int $daytradeCount;

    /**
     * @var float
     */
    private float $daytradingBuyingPower;

    /**
     * @var float
     */
    private float $equity;

    /**
     * @var float
     */
    private float $initialMargin;

    /**
     * @var float
     */
    private float $lastEquity;

    /**
     * @var float
     */
    private float $lastMaintenanceMargin;

    /**
     * @var float
     */
    private float $longMarketValue;

    /**
     * @var float
     */
    private float $maintenanceMargin;

    /**
     * @var int
     */
    private int $multiplier;

    /**
     * @var bool
     */
    private bool $patternDayTrader;

    /**
     * @var float
     */
    private float $portfolioValue;

    /**
     * @var float
     */
    private float $regtBuyingPower;

    /**
     * @var float
     */
    private float $shortMarketValue;

    /**
     * @var bool
     */
    private bool $shortingEnabled;

    /**
     * @var int
     */
    private int $sma;

    /**
     * @var string
     */
    private string $status;

    /**
     * @var bool
     */
    private bool $tradeSuspendedByUser;

    /**
     * @var bool
     */
    private bool $tradingBlocked;

    /**
     * @var bool
     */
    private bool $transfersBlocked;

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
     * @return AlpacaAccount
     */
    public function setAccountBlocked(bool $accountBlocked): AlpacaAccount
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
     * @return AlpacaAccount
     */
    public function setAccountNumber(string $accountNumber): AlpacaAccount
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * @return string $brokerageContext
     */
    public function getBrokerage(): string
    {
        return AlpacaConstants::BROKERAGE_CONTEXT;
    }

    /**
     * @param string $brokerage
     */
    public function setBrokerage(string $brokerage): void
    {
        $this->brokerage = $brokerage;
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
    public function setBuyingPower(string $buyingPower): AlpacaAccount
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
    public function setCash(string $cash): AlpacaAccount
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
    public function setCreatedAt(string $createdAt): AlpacaAccount
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
     * @return AlpacaAccount
     */
    public function setCurrency(string $currency): AlpacaAccount
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
     * @return AlpacaAccount
     */
    public function setDaytradeCount(int $daytradeCount): AlpacaAccount
    {
        $this->daytradeCount = $daytradeCount;

        return $this;
    }

    /**
     * @return float
     */
    public function getDaytradingBuyingPower(): float
    {
        return $this->daytradingBuyingPower;
    }

    /**
     * @param string $daytradingBuyingPower
     *
     * @return $this
     */
    public function setDaytradingBuyingPower(string $daytradingBuyingPower): AlpacaAccount
    {
        $this->daytradingBuyingPower = (float) $daytradingBuyingPower;

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
    public function setEquity(string $equity): AlpacaAccount
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
     * @return AlpacaAccount
     */
    public function setId(string $id): AlpacaAccount
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
    public function setInitialMargin(string $initialMargin): AlpacaAccount
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
    public function setLastEquity(string $lastEquity): AlpacaAccount
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
    public function setLastMaintenanceMargin(string $lastMaintenanceMargin): AlpacaAccount
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
    public function setLongMarketValue(string $longMarketValue): AlpacaAccount
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
    public function setMaintenanceMargin(string $maintenanceMargin): AlpacaAccount
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
     * @param string $multiplier
     *
     * @return AlpacaAccount
     */
    public function setMultiplier(string $multiplier): AlpacaAccount
    {
        $this->multiplier = (int) $multiplier;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPatternDayTrader(): bool
    {
        return $this->patternDayTrader;
    }

    /**
     * @param bool $patternDayTrader
     *
     * @return AlpacaAccount
     */
    public function setPatternDayTrader(bool $patternDayTrader): AlpacaAccount
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
    public function setPortfolioValue(string $portfolioValue): AlpacaAccount
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
    public function setRegtBuyingPower(string $regtBuyingPower): AlpacaAccount
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
    public function setShortMarketValue(string $shortMarketValue): AlpacaAccount
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
    public function setShortingEnabled(bool $shortingEnabled): AlpacaAccount
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
     * @param string $sma
     *
     * @return AlpacaAccount
     */
    public function setSma(string $sma): AlpacaAccount
    {
        $this->sma = (int) $sma;

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
     * @return AlpacaAccount
     */
    public function setStatus(string $status): AlpacaAccount
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
     * @return AlpacaAccount
     */
    public function setTradeSuspendedByUser(bool $tradeSuspendedByUser): AlpacaAccount
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
     * @return AlpacaAccount
     */
    public function setTradingBlocked(bool $tradingBlocked): AlpacaAccount
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
     * @return AlpacaAccount
     */
    public function setTransfersBlocked(bool $transfersBlocked): AlpacaAccount
    {
        $this->transfersBlocked = $transfersBlocked;

        return $this;
    }
}
