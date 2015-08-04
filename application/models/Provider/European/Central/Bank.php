<?php


class Application_Model_Provider_European_Central_Bank extends Application_Model_Provider_Abstract
{
    /**
     * @inheritdoc
     */
    protected $_class = 'European_Central_Bank';

    /**
     * @inheritdoc
     */
    protected function _parse()
    {
        return ['Bank'];
    }
}