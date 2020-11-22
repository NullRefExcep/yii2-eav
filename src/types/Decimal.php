<?php


namespace nullref\eav\types;


class Decimal extends Type
{
    public function getConfigProperties()
    {
        return array_merge([
            'decimals' => function ($activeField) {
                return $activeField
                    ->textInput()
                    ->label(\Yii::t('eav', 'Decimals'));
            },
        ], parent::getConfigProperties());
    }


    public function getDisplayFunction($attributeCode, $attributeConfig)
    {
        $decimals = $attributeConfig['config']['decimals'] ?? 2;

        return function ($model) use ($attributeCode, $decimals) {
            $value = $model->{$attributeCode};
            if (is_array($value)) {
                return implode(', ', array_map(function ($value) use ($decimals) {
                    return \Yii::$app->formatter->asDecimal($value, $decimals);
                }, $value));
            }
            return \Yii::$app->formatter->asDecimal($value, $decimals);
        };
    }

}