<?php


namespace nullref\eav\widgets;


use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

abstract class AttributeInput extends ActiveField
{
    /** @var mixed */
    public $config;

    /** @var ActiveField */
    public $field;

    /** @var string */
    public $attribute;

    /** @var string */
    public $label;

    /** @var ActiveForm */
    public $form;

    /** @var Model */
    public $model;

    /**
     *
     */
    public function init()
    {
        parent::init();

        if ($this->field == null) {
            $this->field = $this->form->field($this->model, $this->attribute);
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        $type = ArrayHelper::remove($this->config, 'type');
        return "$type is not supported";
    }

    /**
     * @return mixed|string
     */
    protected function getLabel()
    {
        if ($this->label === null) {
            return $this->config['name'];
        }
        return $this->label;
    }
}
