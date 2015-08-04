<?php

/**
 * Abstract model class
 * @property integer $id
 * @property array $attributes
 */

abstract class Application_Model_Abstract
{
    /**
     * Primary key
     *
     * @var integer
     */
    protected $_id;

    /**
     * @param array|null $attributes
     */
    public function __construct(array $attributes = null)
    {
        if (is_array($attributes)) {
            $this->setAttributes($attributes);
        }
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid model property');
        }
        return $this->$method();
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws Exception
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid model property');
        }
        $this->$method($value);
    }

    /**
     * Get model's attributes
     *
     * This method was made abstract to make the descendant models have an error
     * until the getAttributes() method is defined.
     *
     * @return array
     */
    abstract public function getAttributes();

    /**
     * @param array $attributes Key-value array of new attributes
     * @return $this
     */
    public function setAttributes(array $attributes = [])
    {
        $methods = get_class_methods($this);
        foreach ($attributes as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->_id = (int) $id;
        return $this;
    }

    /**
     * Transform the model into an array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getAttributes();
    }


    /**
     * Transform the model into an array
     *
     * @return array
     */
    public function toJson()
    {
        return Zend_Json::encode(
            $this->getAttributes()
        );
    }


}