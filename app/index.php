<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once 'database/db_connect.php';

use app\services\NBPApiClient;
use app\controllers\CurrencyRatesController;
use app\controllers\CurrencyConvertController;
use app\repositories\CurrencyRatesRepository;
use app\repositories\CurrencyConversionRepository;
use app\services\CurrencyRatesService;
use app\services\CurrencyConvertService;

$conn = DBConnect::getConnection();

$nbpApiClient = new NBPApiClient();
$currencyRatesRepository = new CurrencyRatesRepository($conn);
$currencyConversionRepository = new CurrencyConversionRepository($conn);
$currencyRatesService = new CurrencyRatesService($nbpApiClient, $currencyRatesRepository, $currencyConversionRepository);
$currencyConvertService = new CurrencyConvertService($currencyRatesRepository, $currencyConversionRepository);
$currencyRatesController = new CurrencyRatesController($currencyRatesService);
$currencyConvertController = new CurrencyConvertController($currencyConvertService);

$conversionData = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['fromCurrency']) && !empty($_GET['toCurrency'])) {
    $amount = $_GET['amount'];
    $fromCurrency = $_GET['fromCurrency'];
    $toCurrency = $_GET['toCurrency'];
    $conversionData = $currencyConvertController->convertCurrency($amount, $fromCurrency, $toCurrency);
}

$currencyRatesController->generateCurrencyForm($conversionData);
$currencyConvertController->renderLastCurrencies();
$currencyRatesController->renderCurrencies();

