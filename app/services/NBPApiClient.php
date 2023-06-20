<?php

namespace app\services;

class NBPApiClient {

    public function getExchangeRates() {
        $url = 'http://api.nbp.pl/api/exchangerates/tables/A?format=json';
        $data = file_get_contents($url);
        $currencies = json_decode($data, true);
        
        return $currencies[0]['rates'];
    }
}