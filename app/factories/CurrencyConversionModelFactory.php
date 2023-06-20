<?php

namespace app\factories;

use app\models\CurrencyConversionModel;

class CurrencyConversionModelFactory
{
    public static function createModel($amount, $fromCurrency, $toCurrency, $convertedAmount, $conversionDate): CurrencyConversionModel
    {
        $model = new CurrencyConversionModel();
        $model->setAmount($amount);
        $model->setFromCurrency($fromCurrency);
        $model->setToCurrency($toCurrency);
        $model->setConvertedAmount($convertedAmount);
        $model->setConversionDate($conversionDate);

        return $model;
    }
}
