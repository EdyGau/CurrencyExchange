<?php

namespace app\interfaces;

interface CurrencyRatesControllerInterface
{
    public function renderCurrencies();
    public function generateCurrencyForm($conversionData);
}