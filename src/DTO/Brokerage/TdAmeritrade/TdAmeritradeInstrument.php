<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\TdAmeritrade;

/**
 * Class TdAmeritradeInstrument.
 */
class TdAmeritradeInstrument
{
    /**
     * @var string
     */
    private $symbol;

    /**
     * @var string|null
     */
    private $underlyingSymbol;

    /**
     * @var \DateTime|null
     */
    private $optionExpirationDate;

    /**
     * @var float|null
     */
    private $optionStrikePrice;

    /**
     * @var string|null
     */
    private $putCall;

    /**
     * @var string|null
     */
    private $cusip;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string|null
     */
    private $assetType;

    /**
     * @var \DateTime|null
     */
    private $bondMaturityDate;

    /**
     * @var float|null
     */
    private $bondInterestRate;

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     *
     * @return TdAmeritradeInstrument
     */
    public function setSymbol(string $symbol): TdAmeritradeInstrument
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUnderlyingSymbol(): ?string
    {
        return $this->underlyingSymbol;
    }

    /**
     * @param string|null $underlyingSymbol
     *
     * @return TdAmeritradeInstrument
     */
    public function setUnderlyingSymbol(?string $underlyingSymbol): TdAmeritradeInstrument
    {
        $this->underlyingSymbol = $underlyingSymbol;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getOptionExpirationDate(): ?\DateTime
    {
        return $this->optionExpirationDate;
    }

    /**
     * @param \DateTime|null $optionExpirationDate
     *
     * @return TdAmeritradeInstrument
     */
    public function setOptionExpirationDate(?\DateTime $optionExpirationDate): TdAmeritradeInstrument
    {
        $this->optionExpirationDate = $optionExpirationDate;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getOptionStrikePrice(): ?float
    {
        return $this->optionStrikePrice;
    }

    /**
     * @param float|null $optionStrikePrice
     *
     * @return TdAmeritradeInstrument
     */
    public function setOptionStrikePrice(?float $optionStrikePrice): TdAmeritradeInstrument
    {
        $this->optionStrikePrice = $optionStrikePrice;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPutCall(): ?string
    {
        return $this->putCall;
    }

    /**
     * @param string|null $putCall
     *
     * @return TdAmeritradeInstrument
     */
    public function setPutCall(?string $putCall): TdAmeritradeInstrument
    {
        $this->putCall = $putCall;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCusip(): ?string
    {
        return $this->cusip;
    }

    /**
     * @param string|null $cusip
     *
     * @return TdAmeritradeInstrument
     */
    public function setCusip(?string $cusip): TdAmeritradeInstrument
    {
        $this->cusip = $cusip;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return TdAmeritradeInstrument
     */
    public function setDescription(?string $description): TdAmeritradeInstrument
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAssetType(): ?string
    {
        return $this->assetType;
    }

    /**
     * @param string|null $assetType
     *
     * @return TdAmeritradeInstrument
     */
    public function setAssetType(?string $assetType): TdAmeritradeInstrument
    {
        $this->assetType = $assetType;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getBondMaturityDate(): ?\DateTime
    {
        return $this->bondMaturityDate;
    }

    /**
     * @param \DateTime|null $bondMaturityDate
     *
     * @return TdAmeritradeInstrument
     */
    public function setBondMaturityDate(?\DateTime $bondMaturityDate): TdAmeritradeInstrument
    {
        $this->bondMaturityDate = $bondMaturityDate;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getBondInterestRate(): ?float
    {
        return $this->bondInterestRate;
    }

    /**
     * @param float|null $bondInterestRate
     *
     * @return TdAmeritradeInstrument
     */
    public function setBondInterestRate(?float $bondInterestRate): TdAmeritradeInstrument
    {
        $this->bondInterestRate = $bondInterestRate;

        return $this;
    }
}
