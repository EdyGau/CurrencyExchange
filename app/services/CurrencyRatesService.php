<?php

namespace app\services;

use app\services\NBPApiClient;
use app\repositories\CurrencyRatesRepository;
use app\models\CurrencyRatesModel;

class CurrencyRatesService
{
    private NBPApiClient $nbpApiClient;
    private CurrencyRatesRepository $currencyRatesRepository;

    public function __construct(NBPApiClient $nbpApiClient, CurrencyRatesRepository $currencyRatesRepository)
    {
        $this->nbpApiClient = $nbpApiClient;
        $this->currencyRatesRepository = $currencyRatesRepository;
    }

    public function getAllCurrencies()
    {
        $currencies = $this->currencyRatesRepository->findAll();

        if (!$currencies) {
            $currencies = $this->nbpApiClient->getExchangeRates();
            $this->save($currencies);
        }

        return $currencies;
    }

    public function save($currences)
    {
        foreach ($currences as $currency) {
            $currencyExist = $this->currencyRatesRepository->findByCode($currency['code']);

            if ($currencyExist) {
                $currencyExist->setMid($currency['mid']);

                $this->currencyRatesRepository->update($currencyExist);
            } else {
                $conversion = new CurrencyRatesModel();
                $conversion->setMid($currency['mid']);
                $conversion->setCurrency($currency['currency']);
                $conversion->setCode($currency['code']);

                $this->currencyRatesRepository->save($conversion);
            }
        }
    }
}
