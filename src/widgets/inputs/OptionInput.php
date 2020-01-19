<?php


namespace nullref\eav\widgets\inputs;


use nullref\eav\widgets\AttributeInput;
use yii\helpers\ArrayHelper;

class OptionInput extends AttributeInput
{
    function run()
    {
        $items = ArrayHelper::remove($this->config, 'items', []);
        if (isset($this->config['config']['multiple']) && $this->config['config']['multiple']) {
            return $this->field->checkboxList($items, ArrayHelper::remove($this->config, 'options', []));
        }
        return $this->field->dropDownList($items,
            ArrayHelper::remove($this->config, 'options', ['prompt' => '']))->label($this->config['name']);
    }
}
