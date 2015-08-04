<?php

/**
 * Class Application_Model_Provider_Abstract
 *
 * @author Sergey Cherdantsev <liverenemy@gmail.com>
 */
abstract class Application_Model_Provider_Abstract extends Application_Model_Abstract
{
    /**
     * Cache lifetime - DateInterval constructor argument
     *
     * @var string
     */
    protected $_cacheLifeTime = 'P1D';

    /**
     * The part of the Provider's class name
     *
     * @var string
     */
    protected $_class;

    /**
     * Whether this provider is active
     *
     * @var boolean
     */
    protected $_isActive;

    /**
     * Provider's name
     *
     * @var string
     */
    protected $_name;

    /**
     * @return Application_Model_Currency[]|array
     */
    abstract protected function _parse();

    /**
     * @param Application_Model_Currency $baseCurrency
     * @return Application_Model_Currency[]|array
     */
    public function getValues(Application_Model_Currency $baseCurrency)
    {
        $dt = new DateTime();
        $dateInterval = new DateInterval($this->_cacheLifeTime);
        $dt->sub($dateInterval);
        $currencyValueMapper = new Application_Model_CurrencyValueMapper();
        $currencyValues = $currencyValueMapper->fetchAll(
            $currencyValueMapper
                ->getDbTable()
                ->select()
                ->from('currencyValue', '*')
                ->join('currency', 'currency.id = currencyValue.valueCurrencyId', [])
                ->where('createdAt > ?', $dt->getTimestamp())
                ->where('providerId = ?', $this->id)
//                ->where('isActive = 1') // this was commented for Angular.JS wow-effect in the User Interface
        );
        if (empty($currencyValues) || empty(count($currencyValues))) {
            $currencyValues = $this->_parse();
        }
        if (!empty($currencyValues) && is_array($currencyValues) && !empty(count($currencyValues))) {
            /** @var Application_Model_CurrencyValue $value */
            foreach ($currencyValues as $value) {
                if ($value->valueCurrencyId == $baseCurrency->id) {
                    $coefficient = $value->value;
                    break;
                }
            }
        }
        if (!empty($coefficient)) {
            /** @var Application_Model_CurrencyValue $item */
            foreach ($currencyValues as &$item) {
                $item
                    ->setValue($coefficient / $item->value)
                    ->setBaseCurrencyId($baseCurrency->id)
                ;
            }
        }
        return $currencyValues;
    }

    /**
     * @inheritdoc
     */
    public function getAttributes()
    {
        return [
            'id'        => $this->getId(),
            'isActive'  => $this->getIsActive(),
            'name'      => $this->getName(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function setAttributes(array $attributes = [])
    {
        unset($attributes['class']);
        return parent::setAttributes($attributes);
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->_class;
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
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->_isActive = (bool) $isActive;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = (string) $name;
    }
}