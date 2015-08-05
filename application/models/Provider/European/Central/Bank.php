<?php


class Application_Model_Provider_European_Central_Bank extends Application_Model_Provider_Abstract
{
    /**
     * @inheritdoc
     */
    protected $_class = 'European_Central_Bank';

    /**
     * Base currency abbr
     *
     * @var string
     */
    protected $_baseCurrencyAbbr = 'EUR';

    /**
     * @inheritdoc
     */
    protected function _parse()
    {
        $mapperCurrency = new Application_Model_CurrencyMapper();
        $currencies = $mapperCurrency->fetchAll();

        if (empty($currencies) || !is_array($currencies) || !count($currencies)) {
            return [];
        }
        /** @var Application_Model_Currency $item */
        foreach ($currencies as $item) {
            if ($item->abbr == $this->_baseCurrencyAbbr) {
                $baseCurrency = $item;
                break;
            }
        }
        if (empty($baseCurrency)) {
            return [];
        }


        $XML=simplexml_load_file("http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml");

        $data = [];

        foreach($XML->Cube->Cube->Cube as $rate){
            $abbr = (string) $rate['currency'];
            $value = ((float) $rate['rate']);
            $data[$abbr] = $value;
        }

        $result = [];
        $mapperCurrencyValue = new Application_Model_CurrencyValueMapper();

        foreach ($currencies as $item) {
            $abbr = $item->abbr;
            if (empty($data[$abbr])) {
                continue;
            }
            $value = new Application_Model_CurrencyValue();
            $value->baseCurrencyId = $baseCurrency->id;
            $value->valueCurrencyId = $item->id;
            $value->value = $data[$abbr];
            $value->providerId = $this->id;
            $mapperCurrencyValue->save($value);
            $result[] = $value;
        }

        return $result;
    }
}