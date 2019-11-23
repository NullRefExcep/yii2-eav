<?php


namespace nullref\eav\widgets\inputs;


use nullref\eav\widgets\AttributeInput;
use yii\helpers\ArrayHelper;

class TextInput extends AttributeInput
{
    function run()
    {
        return $this->field->textarea(ArrayHelper::remove($this->config, 'options', []))->label($this->config['name']);
    }

}