<?php

/**
 * Abstract model mapper
 *
 * @author Sergey Cherdantsev <liverenemy@gmail.com>
 */
abstract class Application_Model_Mapper_Abstract
{
    /**
     * @var Zend_Db_Table_Abstract
     */
    protected $_dbTable;

    /**
     * @var string|Application_Model_Abstract
     */
    protected $_modelClass = '(not set)';

    /**
     * @param array $data
     * @return Application_Model_Abstract
     * @throws Exception
     */
    protected function _createModel(array $data = [])
    {
        $modelClass = $this->_modelClass;
        if (!class_exists($modelClass)) {
            throw new Exception('Incorrect model ' . $modelClass);
        }
        return new $modelClass($data);
    }

    /**
     * Get DbTable class name
     *
     * This method was made abstract to make the descendant classes have an error until the DbTable class is defined
     *
     * @return string|Zend_Db_Table_Abstract
     */
    abstract protected function _getDbTableClass();

    /**
     * @return Application_Model_DbTable_Currency
     * @throws Exception
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable($this->_getDbTableClass());
        }
        return $this->_dbTable;
    }

    /**
     * @param Zend_Db_Table_Abstract|string $dbTable
     * @return $this
     * @throws Exception
     */
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    /**
     * @param Application_Model_Abstract $model
     */
    public function save(Application_Model_Abstract $model)
    {
        $data = $model->getAttributes();

        if (null === ($id = $model->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    /**
     * @param integer $id
     * @return Application_Model_Abstract|null
     * @throws Zend_Db_Table_Exception
     */
    public function find($id)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result) || empty($row = $result->current()) || empty($data = $row->toArray())) {
            return null;
        }
        return $this->_createModel($data);
    }

    /**
     * @param string|array|Zend_Db_Table_Select   $where  OPTIONAL An SQL WHERE clause or Zend_Db_Table_Select object.
     * @param string|array                        $order  OPTIONAL An SQL ORDER clause.
     * @param int                                 $count  OPTIONAL An SQL LIMIT count.
     * @param int                                 $offset OPTIONAL An SQL LIMIT offset.
     * @return array|Application_Model_Abstract[]
     */
    public function fetchAll($where = null, $order = null, $count = null, $offset = null)
    {
        $resultSet = $this->getDbTable()->fetchAll($where, $order, $count, $offset);
        $entries   = array();
        foreach ($resultSet as $row) {
            $data = $row->toArray();
            $model = $this->_createModel($data);
            $entries[] = $model;
        }
        return $entries;
    }
}