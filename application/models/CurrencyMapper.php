<?php

/**
 * Class Application_Model_CurrencyMapper
 *
 * @method Application_Model_Currency[] fetchAll($where = null, $order = null, $count = null, $offset = null)
 * @method Application_Model_Currency find(int $id)
 */
class Application_Model_CurrencyMapper extends Application_Model_Mapper_Abstract
{
    /**
     * @inheritdoc
     */
    protected $_modelClass = 'Application_Model_Currency';

    /**
     * @inheritdoc
     */
    protected function _getDbTableClass()
    {
        return 'Application_Model_DbTable_Currency';
    }

    /**
     * Select IDs of the active currencies
     *
     * @return array|integer[]
     */
    public function selectActiveIds()
    {
        $activeCurrencies = $this->fetchAll(
            '`isActive` = 1'
        );
        $currencyIds = [];
        foreach ($activeCurrencies as $currency) {
            if ($currency->isActive) {
                $currencyIds[] = $currency->id;
            }
        }
        return $currencyIds;
    }
}

