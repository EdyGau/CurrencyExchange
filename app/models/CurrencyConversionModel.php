<?php

namespace app\models;

class CurrencyConversionModel
{
    private $id;
    private float $amount;
    private string $fromCurrency;
    private string $toCurrency;
    private float $convertedAmount;
    private $conversionDate;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getFromCurrency(): string
    {
        return $this->fromCurrency;
    }

    /**
     * @param string $fromCurrency
     */
    public function setFromCurrency(string $fromCurrency): void
    {
        $this->fromCurrency = $fromCurrency;
    }

    /**
     * @return string
     */
    public function getToCurrency(): string
    {
        return $this->toCurrency;
    }

    /**
     * @param string $toCurrency
     */
    public function setToCurrency(string $toCurrency): void
    {
        $this->toCurrency = $toCurrency;
    }

    /**
     * @return float
     */
    public function getConvertedAmount(): float
    {
        return $this->convertedAmount;
    }

    /**
     * @param float $convertedAmount
     */
    public function setConvertedAmount(float $convertedAmount): void
    {
        $this->convertedAmount = $convertedAmount;
    }

    public function getConversionDate()
    {
        return $this->conversionDate;
    }

    public function setConversionDate($conversionDate)
    {
        $this->conversionDate = $conversionDate;
    }
}
