<?php

namespace app\interfaces;


interface CurrencyConvertServiceInterface
{
    public function getLastFiveConversions(): array;

    /**
     * Converts sent currencies.
     * 
     * @param float $amount
     * @param string $from
     * @param string $to
     */
    public function convertCurrency($amount, string $from, string $to);

    /**
     * Creates random currency conversions in the database if the currency conversion table is empty.
     *
     * @param int $size The number of random currency conversions to create.
     */
    public function createRandomCurrenciesConvertions(int $size);
}
