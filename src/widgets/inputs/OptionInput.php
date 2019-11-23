<?php


namespace nullref\eav\widgets\inputs;


use nullref\eav\widgets\AttributeInput;
use yii\helpers\ArrayHelper;

class OptionInput extends AttributeInput
{
    function run()
    {
        return $this->field->dropDownList(ArrayHelper::remove($this->config, 'items', []),
            ArrayHelper::remove($this->config, 'options', ['prompt' => '']))->label($this->config['name']);
    }
}