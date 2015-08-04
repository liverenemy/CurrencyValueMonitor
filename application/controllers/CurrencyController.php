<?php

class CurrencyController extends Zend_Controller_Action
{

    public function activeAction()
    {
        $currencyMapper = new Application_Model_CurrencyMapper();
        Zend_Json::$useBuiltinEncoderDecoder = true;
        $this->_helper->json($currencyMapper->fetchAll('`isActive` = 1'));
    }

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $currencyMapper = new Application_Model_CurrencyMapper();
        $this->view->entries = $currencyMapper->fetchAll();
    }

    public function listAction()
    {
        $currencyMapper = new Application_Model_CurrencyMapper();
        Zend_Json::$useBuiltinEncoderDecoder = true;
        $this->_helper->json($currencyMapper->fetchAll());
    }

    public function activateAction()
    {
        $id = $this->_getParam('id');
        $isActive = !empty($this->_getParam('isActive'));
        if (!empty($id)) {
            $mapper = new Application_Model_CurrencyMapper();
            $currency = $mapper->find($id);
            if ($currency) {
                $currency->setIsActive($isActive);
                $mapper->save($currency);
                Zend_Json::$useBuiltinEncoderDecoder = true;
                $this->_helper->json($currency);
            }
        }
    }
}

