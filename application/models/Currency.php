<?php

/**
 * Class Application_Model_Currency
 * @property string $abbr
 * @property boolean $isActive
 * @property string $name
 */
class Application_Model_Currency extends Application_Model_Abstract
{
    /**
     * Currency short name
     *
     * @var string
     */
    protected $_abbr = '';

    /**
     * Currency name
     *
     * @var string
     */
    protected $_name = '';

    /**
     * Whether the currency is active
     *
     * @var boolean
     */
    protected $_isActive = true;

    /**
     * @inheritdoc
     */
    public function getAttributes()
    {
        return [
            'abbr'      => $this->getAbbr(),
            'id'        => $this->getId(),
            'isActive'  => $this->getIsActive(),
            'name'      => $this->getName(),
        ];
    }

    /**
     * @return string
     */
    public function getAbbr()
    {
        return $this->_abbr;
    }

    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->_isActive;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string $abbr
     * @return Application_Model_Currency
     */
    public function setAbbr($abbr)
    {
        $this->_abbr = (string) $abbr;
        return $this;
    }

    /**
     * @param boolean $isActive
     * @return Application_Model_Currency
     */
    public function setIsActive($isActive)
    {
        $this->_isActive = (bool) $isActive;
        return $this;
    }

    /**
     * @param string $name
     * @return Application_Model_Currency
     */
    public function setName($name)
    {
        $this->_name = (string) $name;
        return $this;
    }

}

