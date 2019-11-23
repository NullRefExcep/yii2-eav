<?php


namespace nullref\eav\widgets;


use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveField;

abstract class AttributeInput extends Widget
{
    /** @var mixed */
    public $config;

    /** @var ActiveField */
    public $field;

    /** @var ActiveField */
    public $label;

    public function run()
    {
        $type = ArrayHelper::remove($config, 'type');
        return "$type is not supported";
    }
}