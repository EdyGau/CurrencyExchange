<?php

namespace app\interfaces;

interface CurrencyConvertControllerInterface
{
    /**
     * Converts currency.
     *
     * @param float $amount Amount to be converted.
     * @param string $from Input currency.
     * @param string $ is the target currency.
     * 
     * @return array An associative array with 'data' or 'error' keys.
     * 'data' key contains converted currency in case of success.
     * The 'error' key contains an error message in case of failure.
     */
    public function convertCurrency(float $amount, string $fromCurrency, string $toCurrency): array;
}
