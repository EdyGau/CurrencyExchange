<?php

namespace app\controllers;

use app\services\CurrencyRatesService;
use app\interfaces\CurrencyRatesControllerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class CurrencyRatesController implements CurrencyRatesControllerInterface
{
    private CurrencyRatesService $currencyRatesService;

    public function __construct(CurrencyRatesService $currencyRatesService)
    {
        $this->currencyRatesService = $currencyRatesService;
    }

    public function renderCurrencies()
    {
        $currencies = $this->getCurrencies();

        $loader = new FilesystemLoader('templates');
        $twig = new Environment($loader);

        echo $twig->render('currencies/tableCurrencyExchange.html.twig', ['currencies' => $currencies]);
    }

    public function generateCurrencyForm($conversionData)
    {
        $currencies = $this->getCurrencies();
        
        $loader = new FilesystemLoader('templates');
        $twig = new Environment($loader);

        echo $twig->render('currencies/currency.html.twig', ['currencies' => $currencies, 'result' => $conversionData]);
    }

    private function getCurrencies()
    {
        return $this->currencyRatesService->getAllCurrencies();
    }
}
