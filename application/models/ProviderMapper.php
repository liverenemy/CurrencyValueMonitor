<?php

/**
 * Class Application_Model_ProviderMapper
 *
 * @author Sergey Cherdantsev <liverenemy@gmail.com>
 *
 * @method Application_Model_Provider_Abstract[] fetchAll($where = null, $order = null, $count = null, $offset = null)
 * @method Application_Model_Provider_Abstract find(int $id)
 */
class Application_Model_ProviderMapper extends Application_Model_Mapper_Abstract
{
    /**
     * @inheritdoc
     */
    protected $_modelClass = 'Application_Model_Provider';

    /**
     * @param array $data
     * @return Application_Model_Provider_Abstract
     * @throws Exception
     */
    protected function _createModel(array $data = [])
    {
        if (empty($data['class'])) {
            throw new Exception('There is no class name to instantiate');
        }
        $modelClass = $this->_modelClass . '_' . $data['class'];
        if (!class_exists($modelClass)) {
            throw new Exception('The specified Provider (' . $data['class'] . ') does not exist');
        }
        return new $modelClass($data);
    }

    /**
     * @inheritdoc
     */
    protected function _getDbTableClass()
    {
        return 'Application_Model_DbTable_Provider';
    }
}

