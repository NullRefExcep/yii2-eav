<?php


namespace nullref\eav\types;


use nullref\eav\events\BuildGridColumnConfigEvent;
use nullref\eav\models\value\StringValue;
use nullref\eav\widgets\inputs\DefaultInput;
use yii\base\Component;

/**
 * Class Type
 * @package nullref\eav\models
 */
class Type extends Component
{
    const EVENT_AFTER_BUILD_GRID_COLUMN_CONFIG = 'after_build_grid_column_config';

    protected $_name;
    protected $_label;
    protected $_valueClass;
    protected $_inputClass;

    /**
     * Type constructor.
     * @param $name
     * @param $label
     * @param $valueClass
     * @param $inputClass
     */
    public function __construct($name, $label, $valueClass = null, $inputClass = null)
    {
        $this->_name = $name;
        $this->_label = $label;
        $this->_valueClass = $valueClass ?? StringValue::class;
        $this->_inputClass = $inputClass ?? DefaultInput::class;
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
     * @param $class
     */
    public function setInputClass($class)
    {
        $this->_inputClass = $class;
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
     * @param $attributeCode
     * @param $attributeConfig
     * @param null $searchModel
     * @return mixed
     */
    public function getGridColumnConfig($attributeCode, $attributeConfig, $searchModel = null)
    {
        $column = [
            'label' => $attributeConfig['name'],
            'attribute' => $attributeCode,
        ];

        $column['value'] = $this->getDisplayFunction($attributeCode, $attributeConfig);

        $event = new BuildGridColumnConfigEvent([
            'column' => $column,
            'code' => $attributeCode,
            'attributeConfig' => $attributeConfig,
            'searchModel' => $searchModel,
        ]);

        $this->trigger(self::EVENT_AFTER_BUILD_GRID_COLUMN_CONFIG, $event);

        return $event->column;
    }

    /**
     * @param $attributeCode
     * @param $attributeConfig
     * @return \Closure
     */
    public function getDisplayFunction($attributeCode, $attributeConfig)
    {
        return function ($model) use ($attributeCode) {
            $value = $model->{$attributeCode};
            if (is_array($value)) {
                return implode(', ', $value);
            }
            return $value;
        };
    }
}