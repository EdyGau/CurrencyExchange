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

    /**
     * Converts currency.
     *
     * @param float $amount Amount to be converted.
     * @param string $from Input currency.
     * @param string $ is the target currency.
     * @return array An associative array with 'data' or 'error' keys.
     * 'data' key contains converted currency in case of success.
     * The 'error' key contains an error message in case of failure.
     */
    public function convertCurrency(float $amount, string $fromCurrency, string $toCurrency): array
    {
        return $this->currencyConvertService->convertCurrency($amount, $fromCurrency, $toCurrency);
    }

    /**
     * Renders recently converted currencies.
     */
    public function renderLastCurrencies()
    {
        $lastConvertCurrencies = $this->currencyConvertService->getLastFiveConversions();

        $loader = new FilesystemLoader('app/templates');
        $twig = new Environment($loader);

        echo $twig->render('currencies/lastConvertedCurrencies.html.twig', ['lastCurrencies' => $lastConvertCurrencies]);
    }
}
