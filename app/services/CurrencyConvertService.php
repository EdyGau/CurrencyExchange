<?php

namespace app\services;

use app\models\CurrencyConversionModel;
use app\repositories\CurrencyRatesRepository;
use app\repositories\CurrencyConversionRepository;
use app\factories\CurrencyConversionModelFactory;

class CurrencyConvertService
{
    private CurrencyRatesRepository $currencyRatesRepository;
    private CurrencyConversionRepository $currencyConversionRepository;

    public function __construct(CurrencyRatesRepository $currencyRatesRepository, CurrencyConversionRepository $currencyConversionRepository)
    {
        $this->currencyRatesRepository = $currencyRatesRepository;
        $this->currencyConversionRepository = $currencyConversionRepository;
    }

    public function getLastFiveConversions(): array
    {
        return $this->currencyConversionRepository->findLastFiveConversion();
    }

    /**
     * Converts sent currencies.
     * 
     * @param float $amount
     * @param string $from
     * @param string $to
     */
    public function convertCurrency($amount, $from, $to)
    {
        $validationResult = $this->validateConversion($amount);
        
        if ($validationResult === true) {
            $fromCurrency = $this->currencyRatesRepository->findByCode($from);
            $toCurrency = $this->currencyRatesRepository->findByCode($to);

            $conversionExist = $this->currencyConversionRepository->findByConversion($amount, $from, $to);

            try {
                $convertedAmount = $amount * ($fromCurrency['mid'] / $toCurrency['mid']);

                $conversion = new CurrencyConversionModel();
                $conversion->setAmount($amount);
                $conversion->setFromCurrency($fromCurrency['code']);
                $conversion->setToCurrency($toCurrency['code']);
                $conversion->setConvertedAmount($convertedAmount);
                $conversion->setConversionDate(date('Y-m-d H:i:s'));

                if ($conversionExist) {
                    $this->currencyConversionRepository->updateCurrencyConverted($conversionExist['id'], $conversion);
                } else {
                    $this->currencyConversionRepository->saveCurrencyConverted($conversion);
                }

                return $conversion;
            } catch (\Exception $e) {
                return ['error' => $e->getMessage()];
            }
        }
    }

    /**
     * Validates that the amount field has the correct value.
     * 
     * @param float $amount
     */
    private function validateConversion($amount)
    {
        if (empty($amount) || !preg_match('/^\d+(\.\d+)?$/', $amount) || strlen($amount) < 1) {
            echo "<p class='alert alert-danger text-center'>Please enter a valid number for the amount!</p>";
            return false;
        }

        return true;
    }

    /**
     * Creates in DB five random currency conversions.
     */
    public function createFirstFiveCurrenciesConvertions()
    {
        for ($i = 0; $i < 5; $i++) {
            $amount = rand(1, 100);
            $fromCurrency = 'USD';
            $toCurrency = 'EUR';
            $convertedAmount = 27;
            $conversionDate = date('Y-m-d');

            $randomCurrency = CurrencyConversionModelFactory::createModel($amount, $fromCurrency, $toCurrency, $convertedAmount, $conversionDate);
            $this->currencyConversionRepository->saveCurrencyConverted($randomCurrency);
        }
    }
}
