<?php

class ProviderController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function diagramAction()
    {
        // action body
    }

    public function indexAction()
    {
        $providerMapper = new Application_Model_ProviderMapper();
        $currencyMapper = new Application_Model_CurrencyMapper();
        $currencies = $currencyMapper->fetchAll('`abbr` = \'USD\'');
        if (!empty($currencies) && is_array($currencies) && count($currencies)) {
            $this->view->assign([
                'baseCurrency' => $currencies[0],
            ]);
        }
        $providers = $providerMapper->fetchAll();
        $this->view->entries = $providers;
    }

    public function listAction()
    {
        $mapper = new Application_Model_ProviderMapper();
        Zend_Json::$useBuiltinEncoderDecoder = true;
        $this->_helper->json($mapper->fetchAll());
    }

    public function oneAction()
    {
        $providerId = $this->_getParam('provider');
        if (!empty($providerId)) {
            $mapperProvider = new Application_Model_ProviderMapper();
            $provider = $mapperProvider->find($providerId);
            $this->view->assign('provider', $provider);
        }
        $baseCurrencyId = $this->_getParam('baseCurrency');
        if (!empty($baseCurrencyId)) {
            $mapperCurrency = new Application_Model_CurrencyMapper();
            $baseCurrency = $mapperCurrency->find($baseCurrencyId);
            $this->view->assign('baseCurrency', $baseCurrency);
        }

        if (!empty($provider) && !empty($baseCurrency)) {
            $values = $provider->getValues($baseCurrency);
            Zend_Json::$useBuiltinEncoderDecoder = true;
            $this->_helper->json($values);
            $this->view->assign([
                'values' => $values,
            ]);
        }

        $this->view->assign([
            'baseCurrencyId' => $baseCurrencyId,
            'providerId' => $providerId,
        ]);
    }


}

