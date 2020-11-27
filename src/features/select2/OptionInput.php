<?php


namespace nullref\eav\features\select2;


use kartik\select2\Select2;
use nullref\eav\widgets\inputs\OptionInput as BaseOptionInput;
use yii\helpers\ArrayHelper;

class OptionInput extends BaseOptionInput
{
    function run()
    {
        $items = ArrayHelper::remove($this->config, 'items', []);
        return $this->field
            ->widget(Select2::class, [
                'data' => $items,
                'options' => [
                    'placeholder' => ArrayHelper::remove($this->config, 'placeholder', ''),
                    'multiple' => isset($this->config['config']['multiple']) && $this->config['config']['multiple'],
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])
            ->label($this->getLabel());
    }
}
