<?php

/**
 * Class Application_Model_Provider_Abstract
 *
 * @author Sergey Cherdantsev <liverenemy@gmail.com>
 *
 * @property boolean $isActive
 * @property string $name
 * @property array $supportedCurrencyIds
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
     * Get the IDs of the currencies supported by this Provider
     *
     * @return array|integer[]
     */
    public function getSupportedCurrencyIds()
    {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $select = $dbAdapter
            ->select()
            ->distinct()
            ->from('currencyValue', 'valueCurrencyId')
            ->where('providerId = ?', $this->id)
            ;
        return $dbAdapter->fetchCol($select);
    }

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
//                ->where('isActive = 1') // this is filtered by Angular.JS on the client side
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

    public function toJson()
    {
        $data = $this->getAttributes();
        $data['supportedCurrencyIds'] = $this->getSupportedCurrencyIds();
        return Zend_Json::encode(
            $data
        );
    }
}