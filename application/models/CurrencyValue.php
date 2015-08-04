<?php

/**
 * Class Application_Model_CurrencyValue
 *
 * @property integer $baseCurrencyId
 * @property Application_Model_Currency $baseCurrency
 * @property integer $valueCurrencyId
 * @property Application_Model_Currency $valueCurrency
 * @property float $value
 * @property integer $providerId
 * @property Application_Model_Provider $provider
 * @property integer $createdAt
 */
class Application_Model_CurrencyValue extends Application_Model_Abstract
{
    /**
     * Base currency primary key
     *
     * @var integer
     */
    protected $_baseCurrencyId;

    /**
     * Base currency
     *
     * @var Application_Model_Currency
     */
    protected $_baseCurrency;

    /**
     * Value currency primary key
     *
     * @var integer
     */
    protected $_valueCurrencyId;

    /**
     * Value currency
     *
     * @var Application_Model_Currency
     */
    protected $_valueCurrency;

    /**
     * Value
     *
     * @var float
     */
    protected $_value;

    /**
     * Currency value Provider's primary key
     *
     * @var integer
     */
    protected $_providerId;

    /**
     * Currency value Provider
     *
     * @var Application_Model_Provider_Abstract
     */
    protected $_provider;

    /**
     * Created time UNIX timestamp
     *
     * @var integer
     */
    protected $_createdAt;

    /**
     * Get a Currency model with specified ID
     *
     * @param integer $id
     * @return Application_Model_Currency|null
     */
    protected function _getCurrency($id)
    {
        $currencyMapper = new Application_Model_CurrencyMapper();
        return $currencyMapper->find($id);
    }

    /**
     * @inheritdoc
     */
    public function getAttributes()
    {
        return [
            'id'                => $this->getId(),
            'baseCurrencyId'    => $this->getBaseCurrencyId(),
            'createdAt'         => $this->getCreatedAt(),
            'valueCurrencyId'   => $this->getValueCurrencyId(),
            'value'             => $this->getValue(),
            'providerId'        => $this->getProviderId(),
        ];
    }

    /**
     * @return int
     */
    public function getBaseCurrencyId()
    {
        return $this->_baseCurrencyId;
    }

    /**
     * @param int $baseCurrencyId
     * @return $this
     */
    public function setBaseCurrencyId($baseCurrencyId)
    {
        if ($baseCurrencyId != $this->getBaseCurrencyId()) {
            unset($this->_baseCurrency);
            $this->_baseCurrencyId = (int) $baseCurrencyId;
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getValueCurrencyId()
    {
        return $this->_valueCurrencyId;
    }

    /**
     * @param int $valueCurrencyId
     * @return $this
     */
    public function setValueCurrencyId($valueCurrencyId)
    {
        if ($valueCurrencyId != $this->getValueCurrencyId()) {
            unset($this->_valueCurrency);
            $this->_valueCurrencyId = (int) $valueCurrencyId;
        }
        return $this;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * @param float $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->_value = (float) $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getProviderId()
    {
        return $this->_providerId;
    }

    /**
     * @param int $providerId
     * @return $this
     */
    public function setProviderId($providerId)
    {
        if ($providerId != $this->getProviderId()) {
            unset($this->_provider);
            $this->_providerId = (int) $providerId;
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getCreatedAt()
    {
        return $this->_createdAt;
    }

    /**
     * @param int $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->_createdAt = (int) $createdAt;
        return $this;
    }

    /**
     * @return Application_Model_Currency
     */
    public function getBaseCurrency()
    {
        $id = $this->getBaseCurrencyId();
        if (empty($this->_baseCurrency) && !empty($id)) {
            $currency = $this->_getCurrency($id);
            if (!empty($currency) && $currency instanceof Application_Model_Currency) {
                $this->_baseCurrency = $currency;
            }
        }
        return $this->_baseCurrency;
    }

    /**
     * @return Application_Model_Currency
     */
    public function getValueCurrency()
    {
        $id = $this->getValueCurrencyId();
        if (empty($this->_baseCurrency) && !empty($id)) {
            $currency = $this->_getCurrency($id);
            if (!empty($currency) && $currency instanceof Application_Model_Currency) {
                $this->_valueCurrency = $currency;
            }
        }
        return $this->_valueCurrency;
    }

    /**
     * @return Application_Model_Provider_Abstract
     */
    public function getProvider()
    {
        $id = $this->getProviderId();
        if (empty($this->_provider) && !empty($id)) {
            $providerMapper = new Application_Model_ProviderMapper();
            $provider = $providerMapper->find($id);
            if (!empty($provider->getId())) {
                $this->_provider = $provider;
            }
        }
        return $this->_provider;
    }
}

