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
        return $this->currencyConversionRepository->findLastCurrencyConversions();
    }

    /**
     * Converts sent currencies.
     * 
     * @param float $amount
     * @param string $from
     * @param string $to
     */
    public function convertCurrency($amount, string $from, string $to)
    {
        $validationResult = $this->validateConversion($amount);

        $isValid = $validationResult['isValid'];
        $errorMessage = $validationResult['errorMessage'];

        if ($isValid === true) {
            $fromCurrency = $this->currencyRatesRepository->findByCode($from);
            $toCurrency = $this->currencyRatesRepository->findByCode($to);

            $conversionExist = $this->currencyConversionRepository->findByConversion($amount, $from, $to);

            try {
                $convertedAmount = $amount * ($fromCurrency->mid / $toCurrency->mid);

                $conversion = new CurrencyConversionModel();
                $conversion->setAmount($amount);
                $conversion->setFromCurrency($fromCurrency->code);
                $conversion->setToCurrency($toCurrency->code);
                $conversion->setConvertedAmount($convertedAmount);
                $conversion->setConversionDate(date('Y-m-d H:i:s'));

                if ($conversionExist) {
                    $this->currencyConversionRepository->updateCurrencyConverted($conversion);
                } else {
                    $this->currencyConversionRepository->saveCurrencyConverted($conversion);
                }

                return ['data' => $conversion];
            } catch (\Exception $e) {
                return ['error' => $e->getMessage()];
            }
        } else {
            return ['error' => $errorMessage];
        }
    }

    /**
     * Creates random currency conversions in the database if the currency conversion table is empty.
     */
    public function createRandomCurrenciesConvertions(int $size)
    {
        $conversionDataExist = $this->currencyConversionRepository->hasDataInTable();

        if ($conversionDataExist === false) {
            for ($i = 0; $i < $size; $i++) {
                $amount = rand(1, 100);
                $fromCurrency = 'USD';
                $toCurrency = 'EUR';
                $convertedAmount = $this->convertCurrency($amount, $fromCurrency, $toCurrency);
                $conversionDate = date('Y-m-d');

                $randomCurrency = CurrencyConversionModelFactory::createModel($amount, $fromCurrency, $toCurrency, $convertedAmount, $conversionDate);
                $this->currencyConversionRepository->saveCurrencyConverted($randomCurrency);
            }
        }
    }

    /**
     * Validates that the amount field has the correct value.
     * 
     * @param $amount
     */
    private function validateConversion($amount): array
    {
        $isValid = true;
        $errorMessage = '';

        if (empty($amount) || !preg_match('/^\d+(\.\d+)?$/', $amount) || strlen($amount) < 1) {
            $isValid = false;
            $errorMessage = "Please enter a valid number for the amount!";
        }

        return ['isValid' => $isValid, 'errorMessage' => $errorMessage];
    }
}
