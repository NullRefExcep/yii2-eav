<?php


namespace nullref\eav\types;


use nullref\eav\models\value\StringValue;

/**
 * Class Type
 * @package nullref\eav\models
 */
class Type
{
    protected $_name;
    protected $_label;

    /**
     * Type constructor.
     * @param $name
     * @param $label
     * @param $valueClass
     * @param $inputClass
     */
    public function __construct($name, $label, $valueClass, $inputClass)
    {
        $this->_name = $name;
        $this->_label = $label;
        $this->_valueClass = $valueClass ?? StringValue::class;
        $this->_inputClass = $inputClass ?? StringValue::class;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->_label;
    }

    /**
     * @return string
     */
    public function getValueClass()
    {
        return $this->_valueClass;
    }

    /**
     * @return string
     */
    public function getInputClass()
    {
        return $this->_inputClass;
    }

    /**
     * @return bool
     */
    public function hasOptions()
    {
        return false;
    }

    /**
     * @return array
     */
    public function getConfigProperties()
    {
        return [];
    }

    /**
     * @param $rawValue
     * @param $attributeConfig
     * @return mixed
     */
    public function getDisplayValue($rawValue, $attributeConfig)
    {
        return $rawValue;
    }

    /**
     * @param $rawValue
     * @param $attributeConfig
     * @return mixed
     */
    public function getGridValue($rawValue, $attributeConfig)
    {
        return $rawValue;
    }
}