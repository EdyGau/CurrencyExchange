<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../src/database/db_connect.php';

use app\services\CurrencyConvertService;
use app\repositories\CurrencyRatesRepository;
use app\repositories\CurrencyConversionRepository;

define('RANDOM_CURRENCIES_SIZE', 5);

$conn = DBConnect::getConnection();

$currencyRatesRepository = new CurrencyRatesRepository($conn);
$currencyConversionRepository = new CurrencyConversionRepository($conn);
$currencyConvertService = new CurrencyConvertService($currencyRatesRepository, $currencyConversionRepository);

$currencyConvertService->createRandomCurrenciesConvertions(RANDOM_CURRENCIES_SIZE);
?>