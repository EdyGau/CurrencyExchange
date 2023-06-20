<?php

namespace app\controllers;

use app\services\CurrencyConvertService;
use app\interfaces\CurrencyConvertControllerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class CurrencyConvertController implements CurrencyConvertControllerInterface
{
    private CurrencyConvertService $currencyConvertService;

    public function __construct(CurrencyConvertService $currencyConvertService)
    {
        $this->currencyConvertService = $currencyConvertService;
    }

    public function convertCurrency($amount, $fromCurrency, $toCurrency)
    {
        return $this->currencyConvertService->convertCurrency($amount, $fromCurrency, $toCurrency);
    }

    public function renderLastCurrencies()
    {
        $lastConvertCurrencies = $this->currencyConvertService->getLastFiveConversions();

        $loader = new FilesystemLoader('templates');
        $twig = new Environment($loader);

        echo $twig->render('currencies/lastConvertedCurrencies.html.twig', ['lastCurrencies' => $lastConvertCurrencies]);
    }
}
