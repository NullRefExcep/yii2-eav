<?php


namespace nullref\eav\models;


use nullref\eav\models\value\StringValue;

/**
 * Class Type
 * @package nullref\eav\models
 */
class Type
{
    protected $_name;
    protected $_label;

    public function getName()
    {
        return $this->_name;
    }

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

    public function __construct($name, $label, $valueClass, $inputClass)
    {
        $this->_name = $name;
        $this->_label = $label;
        $this->_valueClass = $valueClass ?? StringValue::class;
        $this->_inputClass = $inputClass ?? StringValue::class;
    }

    public function hasOptions()
    {
        return false;
    }

}