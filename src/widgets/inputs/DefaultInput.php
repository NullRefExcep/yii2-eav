<?php


namespace nullref\eav\widgets\inputs;


use nullref\eav\widgets\AttributeInput;
use yii\helpers\ArrayHelper;

class DefaultInput extends AttributeInput
{
    function run()
    {
        return $this->field->textInput(ArrayHelper::remove($this->config, 'options', []))->label($this->config['name']);
    }

}