<?php


namespace nullref\eav\widgets\inputs;


use nullref\eav\widgets\AttributeInput;
use unclead\multipleinput\MultipleInput;
use yii\helpers\ArrayHelper;

class MultilineInput extends AttributeInput
{
    function run()
    {
        return $this->field->widget(ArrayHelper::remove($this->config, 'widget_class', MultipleInput::class),
            ArrayHelper::remove($this->config, 'widget_options', []))->label($this->config['name']);
    }

}