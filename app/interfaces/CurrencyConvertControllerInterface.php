<?php

namespace app\interfaces;

interface CurrencyConvertControllerInterface
{
    public function convertCurrency($amount, $fromCurrency, $toCurrency);
}
