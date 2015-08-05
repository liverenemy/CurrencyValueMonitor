<?php


class Application_Model_Provider_Open_Exchange_Rates extends Application_Model_Provider_Abstract
{
    /**
     * @var string
     */
    protected $_class = 'Open_Exchange_Rates';

    protected function _parse()
    {
        // Requested file
        // Could also be e.g. 'currencies.json' or 'historical/2011-01-01.json'
        $file = 'latest.json';
//        $file = 'currencies.json';
        $appId = '7f18930a65f14a4db354035a1c6f5c99';

        // Open CURL session:
        $ch = curl_init("https://openexchangerates.org/api/{$file}?app_id={$appId}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Get the data:
        $json = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response:
        $exchangeRates = json_decode($json);

        $rates = $exchangeRates->rates;

        $result = [];

        $currencyMapper = new Application_Model_CurrencyMapper();
        $currencyValueMapper = new Application_Model_CurrencyValueMapper();
        $currencies = $currencyMapper->fetchAll();

        $baseCurrency = null;
        $coefficient = 0;

        foreach ($currencies as $currency) {
            if ($currency->abbr == $exchangeRates->base) {
                $abbr = $currency->abbr;
                $baseCurrency = $currency;
                $coefficient = $rates->$abbr;
            }
        }

        if (empty($baseCurrency) || empty($coefficient)) {
            return [];
        }

        foreach ($currencies as $currency) {
            $abbr = $currency->abbr;
            if (empty($rates->$abbr)) {
                continue;
            }
            $currencyValue = new Application_Model_CurrencyValue();
            $currencyValue->valueCurrencyId = $currency->id;
            $currencyValue->baseCurrencyId = $baseCurrency->id;
            $currencyValue->value = $exchangeRates->rates->$abbr / $coefficient;
            $currencyValue->providerId = $this->id;
            $currencyValueMapper->save($currencyValue);
            $result[] = $currencyValue;
        }

        return $result;
    }
}