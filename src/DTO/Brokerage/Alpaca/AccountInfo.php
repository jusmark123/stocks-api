<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca;

use App\Constants\Brokerage\AlpacaConstants;
use App\DTO\Brokerage\AccountInfoInterface;

class AccountInfo implements AccountInfoInterface
{
    /**
     * @var string
     */
    private $id;

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
     * @var string
     */
    private $brokerage;

    /**
     * @var int
     */
    private $daytradeCount;

    /**
     * @var float
     */
    private $daytradingBuyingPower;

    /**
     * @var float
     */
    private $equity;

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
     * @return bool
     */
    public function isAccountBlocked(): bool
    {
        return $this->accountBlocked;
    }

    /**
     * @param bool $accountBlocked
     *
     * @return AccountInfo
     */
    public function setAccountBlocked(bool $accountBlocked): AccountInfo
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
     * @return AccountInfo
     */
    public function setAccountNumber(string $accountNumber): AccountInfo
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
    public function setBuyingPower(string $buyingPower): AccountInfo
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
    public function setCash(string $cash): AccountInfo
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
    public function setCreatedAt(string $createdAt): AccountInfo
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
     * @return AccountInfo
     */
    public function setCurrency(string $currency): AccountInfo
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
     * @return AccountInfo
     */
    public function setDaytradeCount(int $daytradeCount): AccountInfo
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
    public function setDaytradingBuyingPower(string $daytradingBuyingPower): AccountInfo
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
    public function setEquity(string $equity): AccountInfo
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
     * @return AccountInfo
     */
    public function setId(string $id): AccountInfo
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
    public function setInitialMargin(string $initialMargin): AccountInfo
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
    public function setLastEquity(string $lastEquity): AccountInfo
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
    public function setLastMaintenanceMargin(string $lastMaintenanceMargin): AccountInfo
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
    public function setLongMarketValue(string $longMarketValue): AccountInfo
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
    public function setMaintenanceMargin(string $maintenanceMargin): AccountInfo
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
     * @return AccountInfo
     */
    public function setMultiplier(string $multiplier): AccountInfo
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
     * @return AccountInfo
     */
    public function setPatternDayTrader(bool $patternDayTrader): AccountInfo
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
    public function setPortfolioValue(string $portfolioValue): AccountInfo
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
    public function setRegtBuyingPower(string $regtBuyingPower): AccountInfo
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
    public function setShortMarketValue(string $shortMarketValue): AccountInfo
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
    public function setShortingEnabled(bool $shortingEnabled): AccountInfo
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
     * @return AccountInfo
     */
    public function setSma(string $sma): AccountInfo
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
     * @return AccountInfo
     */
    public function setStatus(string $status): AccountInfo
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
     * @return AccountInfo
     */
    public function setTradeSuspendedByUser(bool $tradeSuspendedByUser): AccountInfo
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
     * @return AccountInfo
     */
    public function setTradingBlocked(bool $tradingBlocked): AccountInfo
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
     * @return AccountInfo
     */
    public function setTransfersBlocked(bool $transfersBlocked): AccountInfo
    {
        $this->transfersBlocked = $transfersBlocked;

        return $this;
    }
}
