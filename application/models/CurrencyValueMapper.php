<?php

/**
 * Class Application_Model_CurrencyValueMapper
 *
 * @author Sergey Cherdantsev <liverenemy@gmail.com>
 *
 * @method Application_Model_CurrencyValue[] fetchAll($where = null, $order = null, $count = null, $offset = null)
 * @method Application_Model_CurrencyValue find(int $id)
 */
class Application_Model_CurrencyValueMapper extends Application_Model_Mapper_Abstract
{
    /**
     * @inheritdoc
     */
    protected $_modelClass = 'Application_Model_CurrencyValue';

    /**
     * @inheritdoc
     */
    protected function _getDbTableClass()
    {
        return 'Application_Model_DbTable_CurrencyValue';
    }

    /**
     * @inheritdoc
     */
    public function save(Application_Model_Abstract $model)
    {
        /** @var Application_Model_CurrencyValue $model */
        if (empty($model->getId())) {
            $dt = new DateTime();
            $model->setCreatedAt($dt->getTimestamp());
        }
        parent::save($model);
    }
}

