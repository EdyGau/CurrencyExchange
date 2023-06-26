<?php

namespace app\services;

use GuzzleHttp\Client;

class NBPApiClient
{
    public function getExchangeRates()
    {
        $client = new Client();
        $url = 'http://api.nbp.pl/api/exchangerates/tables/A?format=json';

        try {
            $response = $client->get($url);

            if ($response->getStatusCode() === 200) {
                $data = $response->getBody()->getContents();
                $currencies = json_decode($data, true);

                return $currencies[0]['rates'];
            }
        } catch (\Exception $e) {
            return [];
        }
    }
}
